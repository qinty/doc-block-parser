<?php

namespace DocBlockParser;

/**
 * Class DocBlockParser
 * @package DocBlockParser
 */
class DocBlockParser
{
    const REGEX_PROPERTY_EXTRACT = '/^\@property\s([\\\|\w\d_]+)([\[|\]]*)\s(\w+)/';

    /**
     * @param $object
     * @return Property[]
     */
    public static function getProperties($object)
    {
        $response = [];

        $oReflection = new \ReflectionClass($object);
        $namespaceUse = NamespaceUse::fromReflectionClass($oReflection);

        foreach (self::getDocBlockLines($oReflection) as $line) {
            preg_match(self::REGEX_PROPERTY_EXTRACT, $line, $matches);

            if ($matches) {
                $type = $namespaceUse->getFullClassName($matches[1]);
                $response[$matches[3]] = Property::build($type, ($matches[2] === '[]'));
            }
        }

        return $response;
    }

    /**
     * @param \ReflectionClass $object
     * @return array
     */
    protected static function getDocBlockLines(\ReflectionClass $oReflection)
    {
        $response = [];
        $lines = explode(PHP_EOL, $oReflection->getDocComment());

        foreach ($lines as $line) {
            $line = trim($line);

            if (!in_array($line, ['//', '/*', '/**', '*/', '*'], false)) {
                while (substr($line, 0, 1) === '*') {
                    $line = substr($line, 1);
                }
                $response[] = trim($line);
            }
        }

        return $response;
    }
}
