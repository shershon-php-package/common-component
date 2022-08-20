<?php


namespace Shershon\Common\Http;


use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Shershon\Common\Http\Contract\LogFormat;

class Format implements LogFormat
{

    public function logFormat(RequestInterface $request, array $options, ?ResponseInterface $response, ?\Exception $reason, float $begTime, float $endTime): array
    {
        $cost = round(($endTime - $begTime) * 100000) / 100;

        $msg = "REQUEST DONE SUCCESS!";
        if ($reason) {
            $msg = "REQUEST DONE FAILURE!";
        }

        $context = [
            'method'   => $request->getMethod(),
            'uri'      => $request->getUri(),
            'header'   => $request->getHeaders(),
            'body'     => $request->getBody(),
            'response' => empty($response) ? '' : $response->getBody()->getContents(),
            'status'   => empty($response) ? '0' : $response->getStatusCode(),
            'errno'    => empty($reason) ? '0' : $reason->getCode(),
            'error'    => empty($reason) ? '' : $reason->getMessage(),
            'cost'     => $cost,
        ];

        return [$msg, $context];
    }

}