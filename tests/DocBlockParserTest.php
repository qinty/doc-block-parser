<?php
namespace DocBlockParser\tests;

use DocBlockParser\DocBlockParser;
use DocBlockParser\tests\support\SampleClassWithDocBlock;
use DocBlockParser\tests\support\SampleClassWithoutDocBlock;

/**
 * Class DocBlockParserTest
 * @package DocBlockParser\tests
 */
class DocBlockParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * doc block is read correctly on documented class
     */
    public function testDocBlockIsReadCorrectlyOnDocumentedClass()
    {
        $objectWithDocBlock = new SampleClassWithDocBlock();
        $documentedProperties = DocBlockParser::getProperties($objectWithDocBlock);

        static::assertEquals(9, count($documentedProperties));

        $arrays = 0;
        foreach ($documentedProperties as $documentedProperty){
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
        $objectWithDocBlock = new SampleClassWithoutDocBlock();
        $objectReflection = new \ReflectionClass($objectWithDocBlock);
        $declaredProperties = $objectReflection->getProperties();
        $documentedProperties = DocBlockParser::getProperties($objectWithDocBlock);

        static::assertNotEquals($declaredProperties, $documentedProperties);
        static::assertEmpty($documentedProperties);
    }
}