<?php

namespace DocBlockParser;

/**
 * Class Property
 * @package DocBlockParser
 */
class Property
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var boolean
     */
    private $isArray;

    /**
     * @var array
     */
    private $basicTypes = [
        'boolean',
        'integer',
        'float',
        'string',
        'array',
        'object',
        'resource',
        'mixed',
        'number',
        'callback'
    ];

    /**
     * @param string $type
     * @param string $brackets
     * @return Property
     */
    public static function build($type, $brackets = '')
    {
        $property = new Property();
        $property->type = $type;
        $property->isArray = (bool)$brackets;

        return $property;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return boolean
     */
    public function isArray()
    {
        return $this->isArray;
    }

    /**
     * @return bool
     */
    public function isBasicType()
    {
        return in_array($this->type, $this->basicTypes);
    }
}