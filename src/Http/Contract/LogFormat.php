<?php

namespace Shershon\Common\Http\Contract;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface LogFormat
{

    public function logFormat(RequestInterface $request, array $options, ?ResponseInterface $response, ?\Exception $reason, float $begTime, float $endTime): array;

}