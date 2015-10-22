[![Build Status](https://travis-ci.org/bogdananton/doc-block-parser.svg?branch=master)](https://travis-ci.org/bogdananton/doc-block-parser)
[![Test Coverage](https://codeclimate.com/github/bogdananton/doc-block-parser/badges/coverage.svg)](https://codeclimate.com/github/bogdananton/doc-block-parser/coverage)
[![Code Climate](https://codeclimate.com/github/bogdananton/doc-block-parser/badges/gpa.svg)](https://codeclimate.com/github/bogdananton/doc-block-parser)

Extracts dynamically set properties for a class. For each property, will specify if is an array or not.
The extracted type is either a basic type or a custom type (with full namespace path).

####Usage

> using the sample class below, when called will display a list of properties

```php
$instance = new \DocBlockParser\tests\support\SampleClassWithDocBlock;
$properties = \DocBlockParser\DocBlockParser::getProperties($instance);

print_r($properties);
// will display:
// Array(
//     [prop1] => DocBlockParser\Property Object
//         [type] => string
//         [isArray] => false
// 
//     [prop2] => DocBlockParser\Property Object
//         [type] => mixed
//         [isArray] => true
// 
//     [prop3] => DocBlockParser\Property Object
//         [type] => \DocBlockParser\tests\support\CustomType
//         [isArray] => true
// 
//     [prop4] => DocBlockParser\Property Object
//         [type] => \DocBlockParser\tests\support\CustomType
//         [isArray] => false
// 
//     [prop5] => DocBlockParser\Property Object
//         [type] => \CustomTypeFromGlobalNamespace
//         [isArray] => false
// 
//     [prop6] => DocBlockParser\Property Object
//         [type] => \Custom\Type\From\GlobalNamespace
//         [isArray] => false
// 
//     [prop7] => DocBlockParser\Property Object
//         [type] => \DocBlockParser\tests\support\CustomTypeWithRelativeNamespace
//         [isArray] => false
// 
//     [prop8] => DocBlockParser\Property Object
//         [type] => \DocBlockParser\Property
//         [isArray] => false
// 
//     [prop9] => DocBlockParser\Property Object
//         [type] => \DocBlockParser\tests\support\SampleClassWithoutDocBlock
//         [isArray] => false
// )
```

Sample class:

```php
namespace DocBlockParser\tests\support;

use DocBlockParser\Property;
use DocBlockParser\tests\support\SampleClassWithoutDocBlock as CustomTypeWithAliasNamespace;

/**
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

    public function __get($name)
    {
        if (array_key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        }
    }
}
```


####Todo

* process @property-read, @property-write and implemented properties' @var docblock