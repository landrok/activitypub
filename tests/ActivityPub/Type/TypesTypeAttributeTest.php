<?php

namespace ActivityPubTest\Type;

use ActivityPub\Type\Core\Activity;
use ActivityPub\Type\Core\Collection;
use ActivityPub\Type\Core\CollectionPage;
use ActivityPub\Type\Core\IntransitiveActivity;
use ActivityPub\Type\Core\Link;
use ActivityPub\Type\Core\ObjectType;
use ActivityPub\Type\Core\OrderedCollection;
use ActivityPub\Type\Core\OrderedCollectionPage;
use ActivityPub\Type\Extended\Actor\Application;
use ActivityPub\Type\Extended\Actor\Group;
use ActivityPub\Type\Extended\Actor\Organization;
use ActivityPub\Type\Extended\Actor\Person;
use ActivityPub\Type\Extended\Actor\Service;
use ActivityPub\Type\Extended\Activity\Accept;
use ActivityPub\Type\Extended\Activity\Add;
use ActivityPub\Type\Extended\Activity\Announce;
use ActivityPub\Type\Extended\Activity\Arrive;
use ActivityPub\Type\Extended\Activity\Block;
use ActivityPub\Type\Extended\Activity\Create;
use ActivityPub\Type\Extended\Activity\Delete;
use ActivityPub\Type\Extended\Activity\Dislike;
use ActivityPub\Type\Extended\Activity\Flag;
use ActivityPub\Type\Extended\Activity\Follow;
use ActivityPub\Type\Extended\Activity\Ignore;
use ActivityPub\Type\Extended\Activity\Invite;
use ActivityPub\Type\Extended\Activity\Join;
use ActivityPub\Type\Extended\Activity\Leave;
use ActivityPub\Type\Extended\Activity\Like;
use ActivityPub\Type\Extended\Activity\Listen;
use ActivityPub\Type\Extended\Activity\Move;
use ActivityPub\Type\Extended\Activity\Offer;
use ActivityPub\Type\Extended\Activity\Question;
use ActivityPub\Type\Extended\Activity\Read;
use ActivityPub\Type\Extended\Activity\Reject;
use ActivityPub\Type\Extended\Activity\Remove;
use ActivityPub\Type\Extended\Activity\TentativeAccept;
use ActivityPub\Type\Extended\Activity\TentativeReject;
use ActivityPub\Type\Extended\Activity\Travel;
use ActivityPub\Type\Extended\Activity\Undo;
use ActivityPub\Type\Extended\Activity\Update;
use ActivityPub\Type\Extended\Activity\View;
use ActivityPub\Type\Extended\Object\Article;
use ActivityPub\Type\Extended\Object\Audio;
use ActivityPub\Type\Extended\Object\Document;
use ActivityPub\Type\Extended\Object\Event;
use ActivityPub\Type\Extended\Object\Image;
use ActivityPub\Type\Extended\Object\Mention;
use ActivityPub\Type\Extended\Object\Note;
use ActivityPub\Type\Extended\Object\Page;
use ActivityPub\Type\Extended\Object\Place;
use ActivityPub\Type\Extended\Object\Profile;
use ActivityPub\Type\Extended\Object\Relationship;
use ActivityPub\Type\Extended\Object\Tombstone;
use ActivityPub\Type\Extended\Object\Video;
use PHPUnit\Framework\TestCase;

class TypesTypeAttributeTest extends TestCase
{
	/**
	 * Valid scenarios provider
	 */
	public function getObjectTypeScenarios()
	{
		# TypeClass, property, expected value
		return [
			[Activity::class, 'type', 'Activity'],
			[Collection::class, 'type', 'Collection'],
			[CollectionPage::class, 'type', 'CollectionPage'],
			[IntransitiveActivity::class, 'type', 'IntransitiveActivity'],
			[Link::class, 'type', 'Link'],
			[ObjectType::class, 'type', 'Object'],
			[OrderedCollection::class, 'type', 'OrderedCollection'],
			[OrderedCollectionPage::class, 'type', 'OrderedCollectionPage'],
			[Application::class, 'type', 'Application'],
			[Group::class, 'type', 'Group'],
			[Organization::class, 'type', 'Organization'],
			[Person::class, 'type', 'Person'],
			[Service::class, 'type', 'Service'],
			[Accept::class, 'type', 'Accept'],
			[Add::class, 'type', 'Add'],
			[Announce::class, 'type', 'Announce'],
			[Arrive::class, 'type', 'Arrive'],
			[Block::class, 'type', 'Block'],
			[Create::class, 'type', 'Create'],
			[Delete::class, 'type', 'Delete'],
			[Dislike::class, 'type', 'Dislike'],
			[Flag::class, 'type', 'Flag'],
			[Follow::class, 'type', 'Follow'],
			[Ignore::class, 'type', 'Ignore'],
			[Invite::class, 'type', 'Invite'],
			[Join::class, 'type', 'Join'],
			[Leave::class, 'type', 'Leave'],
			[Like::class, 'type', 'Like'],
			[Listen::class, 'type', 'Listen'],
			[Move::class, 'type', 'Move'],
			[Offer::class, 'type', 'Offer'],
			[Question::class, 'type', 'Question'],
			[Read::class, 'type', 'Read'],
			[Reject::class, 'type', 'Reject'],
			[Remove::class, 'type', 'Remove'],
			[TentativeAccept::class, 'type', 'TentativeAccept'],
			[TentativeReject::class, 'type', 'TentativeReject'],
			[Travel::class, 'type', 'Travel'],
			[Undo::class, 'type', 'Undo'],
			[Update::class, 'type', 'Update'],
			[View::class, 'type', 'View'],
			[Article::class, 'type', 'Article'],
			[Audio::class, 'type', 'Audio'],
			[Document::class, 'type', 'Document'],
			[Event::class, 'type', 'Event'],
			[Image::class, 'type', 'Image'],
			[Mention::class, 'type', 'Mention'],
			[Note::class, 'type', 'Note'],
			[Page::class, 'type', 'Page'],
			[Place::class, 'type', 'Place'],
			[Profile::class, 'type', 'Profile'],
			[Relationship::class, 'type', 'Relationship'],
			[Tombstone::class, 'type', 'Tombstone'],
			[Video::class, 'type', 'Video'],
		];
	}

	/**
	 * Check that all core objects have a correct type property.
	 * It checks that getter is working well too.
	 * 
	 * @dataProvider      getObjectTypeScenarios
	 */
	public function testObjectTypeScenarios($type, $attr, $value)
	{
		$object = new $type();
		$this->assertEquals($value, $object->{$attr});
	}
	
	/**
	 * Quick test for a special setter
	 */
	public function testObjectSetterGetter()
	{	
		$object = new ObjectType();
		$object->{'@mention'} = 1;
		$this->assertEquals(1, $object->{'@mention'});
	}

	/**
	 * tests getAttributes() method
	 */
	public function testGetAttributes()
	{	
		$object = new Link();

		$expected = [
			'type',
			'id',
			'name',
			'hreflang',
			'mediaType',
			'rel',
			'height',
			'width'
		];

		$this->assertEquals(
			$expected, 
			$object->getAttributes()
		);
	}
}
