<?php


namespace Shershon\Common\Service;


use Shershon\Common\Factory\Contract\FactoryInterface;
use Shershon\Common\Service\Contract\ServiceConfigInterface;

class Service
{

    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * @var ServiceConfigInterface
     */
    protected $config;

    public function __construct(FactoryInterface $factory, ?ServiceConfigInterface $config = null)
    {
        $this->factory = $factory;
        $this->config  = $config;
    }

    protected function assoc($data, $class, $isArray = null)
    {
        if (!is_array($data) or $this->config->isAssoc()) {
            return $data;
        }
        if (!($isArray = $isArray ?? is_int(array_key_first($data)))) {
            $data = [$data];
        }
        $r = array_map(function ($row) use ($class) {
            if (empty($row)) {
                return null;
            }
            return $this->factory->makeEntity($class, $row);
        }, $data);
        return $isArray ? $r : array_shift($r);
    }
}