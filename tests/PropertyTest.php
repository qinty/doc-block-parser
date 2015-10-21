<?php
namespace DocBlockParser\tests;

use DocBlockParser\Property;

class PropertyTest extends \PHPUnit_Framework_Testcase
{
    /**
     * calling build with non empty brackets parameter sets isArray to true
     */
    public function testCallingBuildWithNonEmptyBracketsParameterSetsIsArrayToTrue()
    {
        $type = 'string';
        $property = Property::build($type, '[]');
        static::assertTrue($property->isArray());
    }


    /**
     * calling build with custom type sets the type property
     */
    public function testCallingBuildWithCustomTypeSetsTheTypeProperty()
    {
        $type = 'CustomType';
        $property = Property::build($type);
        static::assertSame($type, $property->getType());
        static::assertFalse($property->isArray());
    }
}