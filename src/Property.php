<?php

namespace DocBlockParser;

/**
 * Class Property
 * @package DocBlockParser
 */
class Property
{
    /** @var string */
    private $type = '';

    /** @var boolean */
    private $isArray = false;

    /** @var array */
    private static $basicTypes = [
        'boolean',
        'integer',
        'float',
        'string',
        'array',
        'object',
        'resource',
        'mixed',
        'number',
        'callback',
        'Closure',
    ];

    /**
     * @param            $type
     * @param bool|false $isArray
     *
     * @return Property
     */
    public static function build($type, $isArray = false)
    {
        if (!$isArray && $type === 'array') {
            $type = 'mixed';
            $isArray = true;
        }

        $response = new self();
        $response->type = $type;
        $response->isArray = (bool)$isArray;

        return $response;
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
        return in_array($this->type, self::$basicTypes);
    }
}