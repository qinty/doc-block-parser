<?php
namespace DocBlockParser\tests\support;

/**
 * Class SampleClassWithDocBlock
 * @package DocBlockParser\tests
 *
 * @property string prop1
 * @property array prop2
 * @property CustomType[] prop3
 * @property CustomType prop4
 * @var string prop5
 * @type string prop6
 */
class SampleClassWithDocBlock
{
    public $prop1;
    public $prop2;
    public $prop3;
    public $prop4;
}