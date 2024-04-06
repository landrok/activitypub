<?php

namespace ActivityPhpTest\Type;

use ActivityPhp\Type\Core\Activity;
use ActivityPhp\Type\Core\Collection;
use ActivityPhp\Type\Core\CollectionPage;
use ActivityPhp\Type\Core\IntransitiveActivity;
use ActivityPhp\Type\Core\Link;
use ActivityPhp\Type\Core\ObjectType;
use ActivityPhp\Type\Core\OrderedCollection;
use ActivityPhp\Type\Core\OrderedCollectionPage;
use ActivityPhp\Type\Extended\Actor\Application;
use ActivityPhp\Type\Extended\Actor\Group;
use ActivityPhp\Type\Extended\Actor\Organization;
use ActivityPhp\Type\Extended\Actor\Person;
use ActivityPhp\Type\Extended\Actor\Service;
use ActivityPhp\Type\Extended\Activity\Accept;
use ActivityPhp\Type\Extended\Activity\Add;
use ActivityPhp\Type\Extended\Activity\Announce;
use ActivityPhp\Type\Extended\Activity\Arrive;
use ActivityPhp\Type\Extended\Activity\Block;
use ActivityPhp\Type\Extended\Activity\Create;
use ActivityPhp\Type\Extended\Activity\Delete;
use ActivityPhp\Type\Extended\Activity\Dislike;
use ActivityPhp\Type\Extended\Activity\Flag;
use ActivityPhp\Type\Extended\Activity\Follow;
use ActivityPhp\Type\Extended\Activity\Ignore;
use ActivityPhp\Type\Extended\Activity\Invite;
use ActivityPhp\Type\Extended\Activity\Join;
use ActivityPhp\Type\Extended\Activity\Leave;
use ActivityPhp\Type\Extended\Activity\Like;
use ActivityPhp\Type\Extended\Activity\Listen;
use ActivityPhp\Type\Extended\Activity\Move;
use ActivityPhp\Type\Extended\Activity\Offer;
use ActivityPhp\Type\Extended\Activity\Question;
use ActivityPhp\Type\Extended\Activity\Read;
use ActivityPhp\Type\Extended\Activity\Reject;
use ActivityPhp\Type\Extended\Activity\Remove;
use ActivityPhp\Type\Extended\Activity\TentativeAccept;
use ActivityPhp\Type\Extended\Activity\TentativeReject;
use ActivityPhp\Type\Extended\Activity\Travel;
use ActivityPhp\Type\Extended\Activity\Undo;
use ActivityPhp\Type\Extended\Activity\Update;
use ActivityPhp\Type\Extended\Activity\View;
use ActivityPhp\Type\Extended\Object\Article;
use ActivityPhp\Type\Extended\Object\Audio;
use ActivityPhp\Type\Extended\Object\Document;
use ActivityPhp\Type\Extended\Object\Event;
use ActivityPhp\Type\Extended\Object\Image;
use ActivityPhp\Type\Extended\Object\Mention;
use ActivityPhp\Type\Extended\Object\Note;
use ActivityPhp\Type\Extended\Object\Page;
use ActivityPhp\Type\Extended\Object\Place;
use ActivityPhp\Type\Extended\Object\Profile;
use ActivityPhp\Type\Extended\Object\Relationship;
use ActivityPhp\Type\Extended\Object\Tombstone;
use ActivityPhp\Type\Extended\Object\Video;
use PHPUnit\Framework\TestCase;

class TypesTypeAttributeTest extends TestCase
{
	/**
	 * Valid scenarios provider
	 */
	public static function getObjectTypeScenarios()
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
}
