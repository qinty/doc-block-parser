<?php

namespace DocBlockParser;

/**
 * Class DocBlockParser
 * @package AvangateBindings\Support
 */
class DocBlockParser
{
    /**
     * @param $object
     * @return Property[]
     */
    public static function getProperties($object)
    {
        $selfObjectReflection = new \ReflectionClass($object);
        $docBlockParts = explode(PHP_EOL, self::filterDocBlock($selfObjectReflection->getDocComment()));
        $properties = [];
        foreach ($docBlockParts as $line) {
            $line = trim($line);
            preg_match('/^\@property\s([\\\|\w\d_]+)([\[|\]]*)\s(\w+)/', $line, $matches);
            if ($matches) {
                $properties[$matches[3]] = Property::build($matches[1], $matches[2]);
            }
        }
        return $properties;
    }

    /**
     * @param $rawDocBlock
     * @return string
     */
    private static function filterDocBlock($rawDocBlock)
    {
        $response = [];
        $lines = explode(PHP_EOL, $rawDocBlock);
        foreach ($lines as $line) {
            $line = trim($line);
            if (!in_array($line, ['//', '/*', '/**', '*/', '*'], false)) {
                while (substr($line, 0, 1) === "*") {
                    $line = substr($line, 1);
                }
                $response[] = $line;
            }
        }
        return implode(PHP_EOL, $response);
    }
}
