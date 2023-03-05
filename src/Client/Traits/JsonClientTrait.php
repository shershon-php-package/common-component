<?php


namespace Shershon\Common\Client\Traits;


use Shershon\Common\Exception\JsonResponseException;

trait JsonClientTrait
{

    use ClientTrait {
        ClientTrait::getBaseHeaders as private pGetBaseHeaders;
    }

    /**
     * @param string $response
     * @return bool|mixed
     * @throws JsonResponseException
     */
    public function handleResponse($response)
    {
        $d = json_decode($response, true);
        if (json_last_error() == JSON_ERROR_NONE) {
            return $d;
        }
        throw new JsonResponseException(json_last_error(), json_last_error_msg(), $response);
    }

    public function getBaseHeaders(): array
    {
        return array_merge($this->pGetBaseHeaders(), [
            'Accept' => "application/json",
        ]);
    }

}