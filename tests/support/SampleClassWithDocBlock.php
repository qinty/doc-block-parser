<?php
namespace DocBlockParser\tests\support;

use DocBlockParser\Property;
use DocBlockParser\tests\support\SampleClassWithoutDocBlock as CustomTypeWithAliasNamespace;

/**
 * Class SampleClassWithDocBlock
 * @package DocBlockParser\tests
 *
 * // basic types
 * @property string prop1
 * @property array prop2
 *
 * // custom types
 * @property CustomType[] prop3                         relative namespace
 * @property CustomType prop4                           relative namespace
 *
 * // namespace resolver
 * @property \CustomTypeFromGlobalNamespace prop5       absolute namespace
 * @property \Custom\Type\From\GlobalNamespace prop6    absolute full namespace
 * @property CustomTypeWithRelativeNamespace prop7      relative namespace
 * @property Property prop8                             fully qualified namespace
 * @property CustomTypeWithAliasNamespace prop9         should get real full namespace
 *
 * // ignored
 * @var string prop10
 * @type string prop11
 */
class SampleClassWithDocBlock
{
    protected $attributes = [];

    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

//    public function __get($name)
//    {
//        if (!array_key_exists($name, $this->attributes)) {
//            return null;
//        }
//        return $this->attributes[$name];
//    }
}
