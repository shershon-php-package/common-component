<?php


namespace Shershon\Common\Client\Traits;


use Shershon\Common\Factory\Contract\FactoryInterface;

trait ClientServiceTrait
{

    protected $configPrefix = "";

    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * @var null|array
     */
    protected $clientConfig = [];

    /**
     * @return array
     */
    public function getClientConfig(): array
    {
        if (empty($this->clientConfig) && !empty($this->configPrefix)) {
            return $this->factory->config($this->configPrefix, []) ?? [];
        } else {
            return $this->clientConfig ?? [];
        }
    }

    /**
     * @param array $clientConfig
     * @return $this
     */
    public function setClientConfig(array $clientConfig)
    {
        $this->clientConfig = $clientConfig;
        return $this;
    }

}