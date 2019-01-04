<?php

namespace ActivityPubTest\Type;

use ActivityPub\Type;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
	/**
	 * Valid scenarios provider
	 */
	public function getShortTypes()
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
	 * 
	 * @dataProvider getShortTypes
	 */
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
	 * 
	 * @expectedException \Exception
	 */
	public function testUndefinedType()
	{
		$class = Type::create('UndefinedType');
	}
}
