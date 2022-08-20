<?php


namespace Shershon\Common\Util;

use ArrayAccess;
use IlluminateAgnostic\Arr\Support\Arr;
use JsonSerializable;

class Entity implements ArrayAccess, JsonSerializable
{

    protected $properties = [];

    protected $mapKeys = [];

    protected $classMapping = [];

    /**
     * BaseEntity constructor.
     * @param array $properties
     */
    public function __construct(array $properties = [])
    {
        foreach ($properties as $k => $v) {
            $this->set($k, $v);
        }
    }

    protected function getMapKeys()
    {
        if (empty($this->mapKeys)) {
            $class = get_class($this);
            $ret   = [];
            do {
                $ret[] = AnnotationManager::getDocCommentProperties($class) ?: [];
            } while (($class = get_parent_class($class)) !== false);
            $this->mapKeys = array_column(array_merge([], ...$ret), null, 'name');
        }
        return $this->mapKeys;
    }

    /**
     * 注册类实现映射
     * @param string|array $key
     * @param string|null $class
     * @return $this
     */
    protected function setClassMapping($key, $class = null)
    {
        if (is_array($key) && is_null($class)) {
            array_map([$this, 'setClassMapping'], array_keys($key), array_values($key));
            return $this;
        }
        $this->classMapping[$this->decorateKey($key)] = $class;
        return $this;
    }

    protected function decorateKey($key)
    {
        return strtolower(Str::toCamelCase($key));
    }

    public function get($key)
    {
        return data_get($this->properties, $this->decorateKey($key));
    }

    public function set($key, $value)
    {
        $decorateKey = $this->decorateKey($key);
        if (!empty($this->classMapping[$decorateKey])) {
            $class = $this->classMapping[$decorateKey];
            $value = $this->makeEntity($class, $value);
        }
        data_set($this->properties, $decorateKey, $value);
    }

    protected function makeEntity($class, $value)
    {
        if ($value instanceof $class) {
            // do nothing jump
        } // 如果是多维数组
        elseif (is_array($value) && is_int(key($value))) {
            $value = array_map(function ($row) use ($class) {
                return Support::getFactory()->makeEntity($class, $row);
            }, $value);
        } else {
            $value = Support::getFactory()->makeEntity($class, $value);
        }
        return $value;
    }

    public function has($key)
    {
        return Arr::exists($this->properties, $this->decorateKey($key));
    }

    public function remove($key)
    {
        Arr::forget($this->properties, $this->decorateKey($key));
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function __set($name, $value)
    {
        $this->set($name, $value);
    }

    public function __isset($name)
    {
        return $this->has($name);
    }

    public function __unset($name)
    {
        $this->remove($name);
    }

    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }

    public function jsonSerialize()
    {
        $r = [];
        foreach ($this->getMapKeys() as $k => $v) {
            if ($this->has($k)) {
                $r[Str::toUnderScore($k)] = $this->get($k);
            }
        }
        return $r;
    }
}