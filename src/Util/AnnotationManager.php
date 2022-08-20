<?php


namespace Shershon\Common\Util;


use ReflectionClass;

class AnnotationManager
{

    protected static $mapAnnotation = [];

    /**
     * 取得注释里面的属性
     * @param $class
     * @return mixed
     * @throws \ReflectionException
     */
    public static function getDocCommentProperties($class)
    {
        if (!isset(self::$mapAnnotation[$class]['property'])) {
            $reflection   = new ReflectionClass($class);
            $docs         = $reflection->getDocComment();
            $listProperty = [];
            foreach (explode("\n", $docs) as $row) {
                $matches = [];
                if (preg_match('/@property(\s+\$?\w+)+/i', $row, $matches)) {
                    $parts = preg_split("/\s+/", $matches[0]);
                    $type  = $name = null;
                    foreach ($parts as $value) {
                        $value = trim($value);
                        if ($value != "@property" && !empty($value)) {
                            is_null($type) && $value{0} != '$' && $type = $value;
                            is_null($name) && $value{0} == '$' && $name = substr($value, 1);
                        }
                    }
                    !is_null($type) && !is_null($name) && $listProperty[] = ['type' => $type, 'name' => $name];
                }
            }
            self::$mapAnnotation[$class]['property'] = $listProperty;
        }

        return self::$mapAnnotation[$class]['property'];
    }

}
