<?php

namespace ActivityPhpTest\Type;

use ActivityPhp\Server\Http\JsonEncoder;
use ActivityPhp\Server\Http\Normalizer;
use ActivityPhp\Type;
use ActivityPhp\TypeFactory;
use Exception;
use PHPUnit\Framework\TestCase;

class AbstractObjectTest extends TestCase
{

    /**
     * @var TypeFactory
     */
    private $typeFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->typeFactory = new TypeFactory(new Type\TypeResolver(), new Type\Validator());
    }

    /**
     * Test valid setters
     */
    public function testValidSetters()
    {

        $type = $this->typeFactory->create('ObjectType');

        $value = 'http://example1.com';
        $type->id = $value;
        $this->assertEquals($value, $type->id);

        $value = 'http://example2.com';
        $type->set('id', $value);
        $this->assertEquals($value, $type->id);
    }

    /**
     * Test valid getters
     */
    public function testValidGetters()
    {
        $type = $this->typeFactory->create('ObjectType');

        $value = 'http://example1.com';
        $type->id = $value;
        $this->assertEquals($value, $type->id);
    }

    /**
     * Getting a non defined should throw an exception
     */
    public function testGetEmptyProperty()
    {
        $this->expectException(Exception::class);

        $object = $this->typeFactory->create('ObjectType');
        $object->myCustomAttribute;
    }

    /**
     * tests getProperties() method
     */
    public function testGetProperties()
    {	
        $expected = [
            'type',
            'id',
            'name',
            'nameMap',
            'href',
            'hreflang',
            'mediaType',
            'rel',
            'height',
            'preview',
            'width'
        ];

        $this->assertEquals(
            $expected, 
            $this->typeFactory->create('Link')->getProperties()
        );
    }

    /**
     * tests toArray() method
     */
    public function testToArrayWithEmptyProperties()
    {	
        $expected = [
            'type' => 'Link',
            'name' => 'An example',
            'href' => 'http://example.com',
        ];

        $object = $this->typeFactory->create('Link', $expected);

        $normalizer = new Normalizer();

        $this->assertEquals(
            $expected,
            $normalizer->normalize($object)
        );
    }

    /**
     * tests toArray() method
     */
    public function testToArrayWithSomePropertiesSet()
    {	
        $expected = [
            'type' => 'Link',
        ];

        $this->assertEquals(
            $expected, 
            (new Normalizer())->normalize($this->typeFactory->create('Link'))
        );
    }


    /**
     * tests toJson() method
     */
    public function testToJson()
    {
        $encoder = new JsonEncoder();
        $normalizer = new Normalizer();

        $this->assertEquals(
            '{"type":"Link"}',
            $encoder->encode(
                $normalizer->normalize(
                    $this->typeFactory->create('Link')
                )
            )
        );
    }

    /**
     * tests toJson() method
     */
    public function testToJsonWithSomeProperties()
    {	
        $expected = [
            'type' => 'Link',
            'name' => 'An example',
            'href' => 'http://example.com',
        ];

        $encoder = new JsonEncoder();
        $normalizer = new Normalizer();

        $this->assertEquals(
            '{"type":"Link","name":"An example","href":"http:\/\/example.com"}',
            $encoder->encode(
                $normalizer->normalize(
                    $this->typeFactory->create($expected)
                )
            )
        );
    }

    /**
     * tests toJson() method and PHP JSON options
     */
    public function testToJsonWithPhpOptions()
    {	
        $expected = [
            'type' => 'Link',
            'name' => 'An example',
            'href' => 'http://example.com',
        ];

        $encoder = new JsonEncoder(JSON_PRETTY_PRINT);
        $normalizer = new Normalizer();

        $this->assertEquals(
            '{
    "type": "Link",
    "name": "An example",
    "href": "http:\/\/example.com"
}',
            $encoder->encode(
                $normalizer->normalize(
                    $this->typeFactory->create($expected)
                )
            )
        );
    }

    /**
     * Tests has() method throws an Exception with $strict=true
     */
    public function testHasStrictCheck()
    {
        $this->expectException(Exception::class);

        $object = $this->typeFactory->create('ObjectType');
        $object->has('UndefinedProperty', true);
    }

    /**
     * Tests has() method returns false with $strict=false
     */
    public function testHasCheck()
    {
        $object = $this->typeFactory->create('ObjectType');
        
        $this->assertFalse($object->has('UndefinedProperty'));
    }
}
