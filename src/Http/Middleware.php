<?php

namespace Shershon\Common\Http;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise\Create;
use GuzzleHttp\Psr7\Utils;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Shershon\Common\Http\Contract\LogFormat;
use Shershon\Common\Util\AES;

final class Middleware
{

    /**
     * 日志
     * @param LoggerInterface $logger
     * @param LogFormat $logFormat
     * @param string $logLevel
     * @return callable
     */
    public static function log(LoggerInterface $logger, LogFormat $logFormat, $logLevel = LogLevel::INFO): callable
    {
        return static function (callable $handler) use ($logger, $logLevel, $logFormat) {
            return static function (RequestInterface $request, array $options) use ($handler, $logger, $logLevel, $logFormat) {
                $begTime = microtime(true);
                return $handler($request, $options)->then(
                    static function (ResponseInterface $response) use ($begTime, $request, $options, $logger, $logLevel, $logFormat) {
                        list($msg, $context) = $logFormat->logFormat($request, $options, $response, null, $begTime, microtime(true));
                        $logger->log($logLevel, $msg, $context);
                        return $response;
                    },
                    static function (\Exception $reason) use ($begTime, $request, $options, $logger, $logLevel, $logFormat) {
                        $response = $reason instanceof RequestException ? $reason->getResponse() : null;
                        list($msg, $context) = $logFormat->logFormat($request, $options, $response, $reason, $begTime, microtime(true));
                        $logger->log($logLevel, $msg, $context);
                        return Create::rejectionFor($reason);
                    }
                );
            };
        };
    }

    public static function aes(string $secret): callable
    {
        return static function (callable $handler) use ($secret) {
            return static function (RequestInterface $request, array $options) use ($handler, $secret) {
                $body       = $request->getBody();
                $bodyEncode = AES::aesEncode((string)$body, $secret);
                $aesRequest = $request->withBody(Utils::streamFor($bodyEncode))
                    ->withHeader("Content-Type", "text/plain");

                return $handler($aesRequest, $options)->then(
                    static function (ResponseInterface $response) use ($secret) {
                        $content = (string)$response->getBody();
                        if (empty($content)) {
                            return $response;
                        }
                        $contentDecode = AES::aesDecode((string)$content, $secret);
                        return $response->withBody(Utils::streamFor($contentDecode))
                            ->withHeader("Content-Type", "application/json");
                    }
                );
            };
        };
    }

}