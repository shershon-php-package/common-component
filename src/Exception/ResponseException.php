<?php


namespace Shershon\Common\Exception;


use Throwable;
use Shershon\Common\Service\Contract\ServiceExceptionInterface;

class ResponseException extends Exception implements ServiceExceptionInterface
{

    /**
     * @var string
     */
    protected $response;

    public function __construct($code = 0, $message = "", string $response = "", ?Throwable $previous = null)
    {
        parent::__construct($code, $message, $previous);
        $this->response = $response;
    }

    /**
     * @return string
     */
    public function getResponse(): string
    {
        return $this->response;
    }

}