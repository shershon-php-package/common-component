<?php


namespace Shershon\Common\Service\Contract;


interface ResponseHandlerInterface
{

    /**
     * @param string $response
     * @return mixed
     */
    public function handleResponse($response);

}