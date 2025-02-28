<?php

namespace ActivityPhpTest\Type;

use ActivityPhp\Type;
use ActivityPhpTest\MyCustomType;
use ActivityPhpTest\MyCustomValidator;
use Exception;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    /**
     * Valid scenarios provider
     */
    public static function getShortTypes()
    {
        # Short type name
        return [
            ['Activity'],
            ['Collection'],
            ['CollectionPage'],
            ['IntransitiveActivity'],
            ['Link'],
            ['ObjectType'],
            ['Object'],
            ['OrderedCollection'],
            ['OrderedCollectionPage'],
            ['Application'],
            ['Group'],
            ['Organization'],
            ['Person'],
            ['Service'],
            ['Accept'],
            ['Add'],
            ['Announce'],
            ['Arrive'],
            ['Block'],
            ['Create'],
            ['Delete'],
            ['Dislike'],
            ['Flag'],
            ['Follow'],
            ['Ignore'],
            ['Invite'],
            ['Join'],
            ['Leave'],
            ['Like'],
            ['Listen'],
            ['Move'],
            ['Offer'],
            ['Question'],
            ['Read'],
            ['Reject'],
            ['Remove'],
            ['TentativeAccept'],
            ['TentativeReject'],
            ['Travel'],
            ['Undo'],
            ['Update'],
            ['View'],
            ['Article'],
            ['Audio'],
            ['Document'],
            ['Event'],
            ['Image'],
            ['Mention'],
            ['Note'],
            ['Page'],
            ['Place'],
            ['Profile'],
            ['Relationship'],
            ['Tombstone'],
            ['Video'],
        ];
	}

    /**
     * Check that all core objects have a correct type property.
     */
    #[DataProvider('getShortTypes')]
    public function testShortTypesInstanciation($type)
    {
        $class = Type::create($type, ['name' => strtolower($type)]);

        // Assert affectation
        $this->assertEquals(
            strtolower($type),
            $class->name
        );

        // Object has two names: Object and ObjectType
        if ($type == 'ObjectType') {
            $type = 'Object';
        }

        // Assert type property
        $this->assertEquals(
            $type,
            $class->type
        );
    }

    /**
     * Scenario for an undefined type
     */
    public function testUndefinedType()
    {
        $this->expectException(Exception::class);

        $class = Type::create('UndefinedType');
    }

    /**
     * Scenario for a custom validator
     *
     * - Add a validator in the pool for 'customProperty' attribute
     * - Create a type with this property and affect a correct value
     */
    public function testCustomValidatorSuccess()
    {
        Type::add('MyCustomType', MyCustomType::class);
        Type::addValidator('customProperty', MyCustomValidator::class);
        $type = Type::create(
            'MyCustomType',
            ['customProperty' => 'My value']
        );

        // Assert type property
        $this->assertEquals(
            'My value',
            $type->customProperty
        );
    }

    /**
     * Scenario for instanciating a Type with a single array parameter
     */
    public function testShortCallSuccess()
    {
        $type = Type::create([
            'type' => 'Note',
            'id' => 'http://example.org/missing-type'
        ]);

        // Assert type property
        $this->assertEquals(
            'Note',
            $type->type
        );

        // Assert another property
        $this->assertEquals(
            'http://example.org/missing-type',
            $type->id
        );
    }

    /**
     * Scenario for instanciating a Type with a single array parameter
     * for a failing value (missing type property)
     */
    public function testShortCallFailing()
    {
        $this->expectException(Exception::class);

        $type = Type::create(
            ['id' => 'http://example.org/missing-type']
        );
    }

    /**
     * Scenario for instanciating a Type with a single parameter that
     * is not an array.
     */
    public function testShortCallFailingIntGiven()
    {
        $this->expectException(Exception::class);

        $type = Type::create(
            42
        );
    }

    /**
     * Scenario for a custom classes and custom validator with an
     * failing value
     */
    public function testCustomValidatorFailing()
    {
        $this->expectException(Exception::class);

        Type::addValidator('customProperty', MyCustomValidator::class);
        $type = Type::create(
            'MyCustomType',
            ['customProperty' => 'Bad value']
        );
    }

    /**
     * Scenario for a custom type
     *
     * - Add a Type in the pool with 'Person' name
     * - Instanciate and sets customType value
     */
    public function testCustomTypeSuccess()
    {
        Type::add('Person', MyCustomType::class);
        $type = Type::create(
            'Person',
            ['customProperty' => 'My value']
        );

        // Assert type property
        $this->assertEquals(
            'My value',
            $type->customProperty
        );
    }

    /**
     * Scenario for a custom classes with a failing value
     */
    public function testCustomTypeFailing()
    {
        $this->expectException(Exception::class);

        Type::add('Person', 'MyUndefinedType');
    }

    /**
     * Test a copy of an AS object
     */
    public function testCopy()
    {
        $original = Type::create([
            'type' => 'Note',
            'id' => 'http://example.org/original-id'
        ]);

        $copy = $original->copy();

        // Assert type are equals
        $this->assertEquals(
            $original->type,
            $copy->type
        );

        // Assert all properties are equals
        $this->assertEquals(
            $original->toArray(),
            $copy->toArray()
        );

        // Change a value
        $copy->id = 'http://example.org/copy-id';

        // Change is ok for the copy
        $this->assertEquals(
            'http://example.org/copy-id',
            $copy->id
        );

        // Assert original is not affected
        $this->assertEquals(
            'http://example.org/original-id',
            $original->id
        );
    }

    /**
     * Test copy chaining
     */
    public function testCopyChaining()
    {
        $original = Type::create([
            'type' => 'Note',
            'id' => 'http://example.org/original-id'
        ]);

        $copy = $original->copy()->setId(
            'http://example.org/copy-id'
        );

        // Change is ok for the copy
        $this->assertEquals(
            'http://example.org/copy-id',
            $copy->id
        );

        // Assert original is not affected
        $this->assertEquals(
            'http://example.org/original-id',
            $original->id
        );
    }

    /**
     * Test creating a type from a JSON string
     */
    public function testFromJson()
    {
        $json = '{"type":"Note","content":"A content for my note"}';

        $note = Type::fromJson($json);

        $this->assertEquals(
            $json,
            $note->toJson()
        );
    }

    /**
     * Test creating a type from a malformed JSON string
     */
    public function testFromJsonMalformedJsonString()
    {
        $this->expectException(Exception::class);

        $json = '{';
        $note = Type::fromJson($json);
    }

    /**
     * Test creating a type from a JSON string which does not contains
     * an array.
     */
    public function testFromJsonNotAnArray()
    {
        $this->expectException(Exception::class);

        $json = '"OK"';
        $note = Type::fromJson($json);
    }
}
