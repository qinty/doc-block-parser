<?php

namespace DocBlockParser\tests;

use DocBlockParser\DocBlockParser;
use DocBlockParser\Property;
use DocBlockParser\tests\support\SampleClassWithDocBlock;
use DocBlockParser\tests\support\SampleClassWithoutDocBlock;

/**
 * Class DocBlockParserTest
 * @package DocBlockParser\tests
 */
class DocBlockParserTest extends \PHPUnit\Framework\TestCase
{
    /**
     * When passing a class with no properties then return empty array.
     */
    public function testWhenPassingAClassWithNoPropertiesThenReturnEmptyArray()
    {
        $underTest = new SampleClassWithoutDocBlock();
        $response  = DocBlockParser::getProperties($underTest);

        static::assertInternalType('array', $response);
        static::assertEmpty($response);
    }

    /**
     * When passing a class with properties then return same number of parameters.
     */
    public function testWhenPassingAClassWithPropertiesThenReturnSameNumberOfParameters()
    {
        $underTest = new SampleClassWithDocBlock();
        $response  = DocBlockParser::getProperties($underTest);

        static::assertInternalType('array', $response);
        static::assertEquals(9, count(array_keys($response)));

        return $response;
    }

    /**
     * When a property is defined as string then treat as a non-array string basic type property.
     * @depends testWhenPassingAClassWithPropertiesThenReturnSameNumberOfParameters
     */
    public function testWhenAPropertyIsDefinedAsStringThenTreatAsANonArrayStringBasicTypeProperty($response)
    {
        /** @var Property $property */
        $property = $response['prop1'];

        static::assertFalse($property->isArray());
        static::assertEquals('string', $property->getType());
        static::assertTrue($property->isBasicType());
    }

    /**
     * When a property is defined as a generic array then treat as an array or mixed basic type property.
     * @depends testWhenPassingAClassWithPropertiesThenReturnSameNumberOfParameters
     */
    public function testWhenAPropertyIsDefinedAsAGenericArrayThenTreatAsAnArrayOrMixedBasicTypeProperty($response)
    {
        /** @var Property $property */
        $property = $response['prop2'];

        static::assertTrue($property->isArray());
        static::assertEquals('mixed', $property->getType());
        static::assertTrue($property->isBasicType());
    }

    /**
     * When a property is defined as custom type array then treat as an array of custom type property.
     * @depends testWhenPassingAClassWithPropertiesThenReturnSameNumberOfParameters
     */
    public function testWhenAPropertyIsDefinedAsCustomTypeArrayThenTreatAsAnArrayOfCustomTypeProperty($response)
    {
        /** @var Property $property */
        $property = $response['prop3'];

        static::assertTrue($property->isArray());
        static::assertEquals('\DocBlockParser\tests\support\CustomType', $property->getType());
        static::assertFalse($property->isBasicType());
    }

    /**
     * When a property is defined as custom type then treat as an non-array custom type property.
     * @depends testWhenPassingAClassWithPropertiesThenReturnSameNumberOfParameters
     */
    public function testWhenAPropertyIsDefinedAsCustomTypeThenTreatAsAnNonArrayCustomTypeProperty($response)
    {
        /** @var Property $property */
        $property = $response['prop4'];

        static::assertFalse($property->isArray());
        static::assertEquals('\DocBlockParser\tests\support\CustomType', $property->getType());
        static::assertFalse($property->isBasicType());
    }

    /**
     * When a property is a custom class from the root global namespace then return the custom type property.
     * @depends testWhenPassingAClassWithPropertiesThenReturnSameNumberOfParameters
     */
    public function testWhenAPropertyIsACustomClassFromTheRootGlobalNamespaceThenReturnTheCustomTypeProperty($response)
    {
        /** @var Property $property */
        $property = $response['prop5'];

        static::assertFalse($property->isArray());
        static::assertEquals('\CustomTypeFromGlobalNamespace', $property->getType());
        static::assertFalse($property->isBasicType());
    }

    /**
     * When a property is a custom class with a global namespace then return the custom type property.
     * @depends testWhenPassingAClassWithPropertiesThenReturnSameNumberOfParameters
     */
    public function testWhenAPropertyIsACustomClassWithAGlobalNamespaceThenReturnTheCustomTypeProperty($response)
    {
        /** @var Property $property */
        $property = $response['prop6'];

        static::assertFalse($property->isArray());
        static::assertEquals('\Custom\Type\From\GlobalNamespace', $property->getType());
        static::assertFalse($property->isBasicType());
    }

    /**
     * When a property is a custom class without a namespace then return type with relative namespace.
     * @depends testWhenPassingAClassWithPropertiesThenReturnSameNumberOfParameters
     */
    public function testWhenAPropertyIsACustomClassWithoutANamespaceThenReturnTypeWithRelativeNamespace($response)
    {
        /** @var Property $property */
        $property = $response['prop7'];

        static::assertFalse($property->isArray());
        static::assertEquals('\DocBlockParser\tests\support\CustomTypeWithRelativeNamespace', $property->getType());
        static::assertFalse($property->isBasicType());
    }

    /**
     * doc block is read correctly on documented class
     */
    public function testDocBlockIsReadCorrectlyOnDocumentedClass()
    {
        $objectWithDocBlock   = new SampleClassWithDocBlock();
        $documentedProperties = DocBlockParser::getProperties($objectWithDocBlock);

        static::assertEquals(9, count($documentedProperties));

        $arrays = 0;
        foreach ($documentedProperties as $documentedProperty) {
            if ($documentedProperty->isArray()) {
                $arrays++;
            }
        }
        static::assertEquals(2, $arrays);
    }

    /**
     * doc block is read correctly on undocumented class
     */
    public function testDocBlockIsReadCorrectlyOnUndocumentedClass()
    {
        $objectWithDocBlock   = new SampleClassWithoutDocBlock();
        $objectReflection     = new \ReflectionClass($objectWithDocBlock);
        $declaredProperties   = $objectReflection->getProperties();
        $documentedProperties = DocBlockParser::getProperties($objectWithDocBlock);

        static::assertNotEquals($declaredProperties, $documentedProperties);
        static::assertEmpty($documentedProperties);
    }
}
