<?php

namespace DocBlockParser;

/**
 * Class DocBlockParser
 * @package DocBlockParser
 */
class DocBlockParser
{
    private const REGEX_PROPERTY_EXTRACT = '/^\@property\s([\\\|\w]+)([\[|\]]*)\s+(\w+)/';

    /**
     * @param $object
     *
     * @return Property[]
     * @throws \ReflectionException
     */
    public static function getProperties($object): array
    {
        $response = [];

        $oReflection  = new \ReflectionClass($object);
        $namespaceUse = NamespaceUse::fromReflectionClass($oReflection);

        foreach (self::getDocBlockLines($oReflection) as $line) {
            preg_match(self::REGEX_PROPERTY_EXTRACT, $line, $matches);

            if ($matches) {
                $type                  = $namespaceUse->getFullClassName($matches[1]);
                $response[$matches[3]] = Property::build($type, ($matches[2] === '[]'));
            }
        }

        return $response;
    }

    /**
     * @param \ReflectionClass $oReflection
     *
     * @return array
     */
    protected static function getDocBlockLines(\ReflectionClass $oReflection): array
    {
        $response = [];
        $lines    = explode(PHP_EOL, $oReflection->getDocComment());

        //fallback for Windows machines
        if (\strlen(PHP_EOL) === 2) {
            $lines = explode("\n", $oReflection->getDocComment());
        }

        foreach ($lines as $line) {
            $line = trim($line);

            if (!\in_array($line, ['//', '/*', '/**', '*/', '*'], false)) {
                while (strpos($line, '*') === 0) {
                    $line = substr($line, 1);
                }
                $response[] = trim($line);
            }
        }

        return $response;
    }
}
