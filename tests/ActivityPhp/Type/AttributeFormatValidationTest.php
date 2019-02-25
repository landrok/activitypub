<?php

namespace ActivityPhpTest\Type;

use ActivityPhp\Type;
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
use ActivityPhp\Type\Validator;
use PHPUnit\Framework\TestCase;

class AttributeFormatValidationTest extends TestCase
{
	/**
	 * Valid scenarios provider
	 */
	public function getValidAttributesScenarios()
	{
        $link = new Link();
        $link->href = 'https://example.com/my-href';
        $note = new Note();
        $note->name = "It's a note";
        $place = new Place();
        $place->name = "Over the Arabian Sea, east of Socotra Island Nature Sanctuary";
        $place->longitude = 12.34;
        $place->latitude = 56.78;
        $place->altitude = 90;
        $place->units = "m";

		# TypeClass, property, value
		return [
['@context', Place::class, 'https://www.w3.org/ns/activitystreams'     ], # Set @context
['accuracy', Place::class, 100                                         ], # Set accuracy (int) 
['accuracy', Place::class, 0                                           ], # Set accuracy (int)
['accuracy', Place::class, '0'                                         ], # Set accuracy (numeric int) 
['accuracy', Place::class, '0.5'                                       ], # Set accuracy (numeric float) 
['actor', Activity::class, 'https://example.com/bob'                   ], # Set actor as URL
['actor', Activity::class,  [
                              "type"   => "Person",
                              "id"     => "http://sally.example.org",
                              "summary"=> "Sally"
                            ]                                          ], # Set actor as an Actor type
['actor', Activity::class, [
                              "http://joe.example.org",
                              [
                                "type" => "Person",
                                "id"   => "http://sally.example.org",
                                "name" => "Sally"
                              ]
                            ]                                          ], # Set actor as multiple actors, JSON encoded
['actor', Activity::class, Type::create('Person', ["name" => "Sally"]) ], # Set actor as an Actor type
['actor', Activity::class, [
                         Type::create('Person', ["name" => "Sally"]),
                         Type::create('Person', ["name" => "Bob"])
                       ]                                               ], # Set actor as an Actor type

['altitude', Place::class, 0.5                                         ], # Set altitude (float)
['anyOf', Question::class, [
                              [
                                "type" => "Note",
                                "name" => "Option A"
                              ],
                              [
                                "type" => "Note",
                                "name" => "Option B"
                              ]
                            ]                                          ], # Set anyOf choices 
['attachment', Note::class, []                                         ], # Set attachment with an empty array
['attachment', Note::class, Type::create(['type'=> 'Object'])          ], # Set attachment with an Object type
['attachment', Note::class, [ Type::create(['type'=> 'Object']) ]      ], # Set attachment with an array of Object types
['attachment', Note::class, [
                               [
                                 "type"    => "Image",
                                 "content" => "This is what he looks like.",
                                 "url"     => "http://example.org/cat.jpeg"
                               ]
                            ]                                          ], # Set attachment with an ObjectType
['attachment', Note::class, [
                              [
                                "type" => "Link",
                                "href" => "http://example.org/cat.jpeg"
                              ]
                            ]                                          ], # Set attachment with an Link
['attachment', Note::class, ["http://example.org/cat.jpeg"]            ], # Set attachment with an indirect reference
['attachment', ObjectType::class, [
                                     [
                                       "type"    => "Image",
                                       "content" => "This is what he looks like.",
                                       "url"     => "http://example.org/cat.jpeg"
                                     ]
                                  ]                                    ], # Set attachment	
['attributedTo', Image::class, [
                                  [
                                    "type" => "Person",
                                    "name" => "Sally",
                                    "id"  => "http://example.org/@sally"
                                  ]
                                ]                                	   ], # Set attributedTo with an array of persons
['attributedTo', Image::class, [
                                  "type" => "Person",
                                  "name" => "Sally",
                                  "id"  => "http://example.org/@sally"
                               ]                                       ], # Set attributedTo with a single actor
['attributedTo', Image::class, [
                                  "type" => "Link",
                                  "href" => "http://joe.example.org"
                               ]                                       ], # Set attributedTo with a Link
['attributedTo', Image::class, [
                                  "http://sally.example.org",
                                  [
                                    "type" => "Person",
                                    "name" => "Sally",
                                    "id"  => "http://example.org/@sally"
                                  ]
                               ]                                       ], # Set attributedTo with an array of mixed URL and persons
['attributedTo', Note::class, 'https://social.example/alyssa/'         ], # Set attributedTo as an URL


['audience', Note::class, [
                             [
                               "type" => "Person",
                               "name" => "Sally"
                             ]
                          ]                                            ], # Set audience with an array of persons
['audience', Note::class, [
                             "type" => "Person",
                             "name" => "Sally"
                          ]                                            ], # Set audience with a single actor
['audience', Note::class, [
                             "type" => "Link",
                             "href" => "http://joe.example.org"
                          ]                                            ], # Set audience with a Link
['audience', Note::class, [
                             "http://sally.example.org",
                             [
                               "type" => "Person",
                               "name" => "Sally"
                             ]
                           ]                                           ], # Set audience with an array of mixed URL and persons
['bcc', Offer::class, [
                        "http://sally.example.org",
                        [
                          "type" => "Person",
                          "name" => "Bob",
                          "url"  => "http://bob.example.org"
                        ]
                       ]                                               ], # Set bcc with an array of mixed URL and persons
['bcc', Offer::class, []                                               ], # Set bcc with an empty array
['bto', Offer::class, [
                        "http://joe.example.org",
                        [
                          "type" => "Person",
                          "name" => "Bob",
                          "url"  => "http://bob.example.org"
                        ]
                       ]                                               ], # Set bto with an array of mixed URL and persons
['bto', Offer::class, []                                                ], # Set bto with an empty array
['cc', Offer::class, [
                       "http://sally.example.org",
                       [
                         "type" => "Person",
                         "name" => "Bob",
                         "url"  => "http://bob.example.org"
                       ]
                      ]                                                ], # Set cc with an array of mixed URL and persons
['cc', Offer::class, []                                                ], # Set cc with an empty array
['closed', Question::class, '2016-05-10T00:00:00Z'                     ], # Set closed as a Datetime
['closed', Question::class, true                                       ], # Set closed as a boolean
['closed', Question::class, 'http://bob.example.org'                   ], # Set closed as a URL
['closed', Question::class, [
                               "type" => "Object",
                               "name" => "Bob",
                               "url" => "http://bob.example.org"
                             ]                                        ], # Set closed as an object
['closed', Question::class, [
                               "type" => "Link",
                               "href" => "http://bob.example.org"
                             ]                                         ], # Set closed as Link
['content', Note::class, 'http://bob.example.org'                      ], # Set content as string
['content', Note::class, null                                          ], # Set content as null
['content', Note::class, 'a <b>strong</b> content'                     ], # Set content as HTML string
['contentMap', Note::class, [
                               "en"      => "A <em>simple</em> note",
                               "es"      => "Una nota <em>sencilla</em>",
                               "zh-Hans" => "一段<em>简单的</em>笔记"
                            ]                                          ], # Set a content map
['context', ObjectType::class, 'http://bob.example.org'                ], # Set context as a URL
['context', ObjectType::class, [
                                 "type" => "Object",
                                 "name" => "Bob",
                                 "url" => "http://bob.example.org"
                                ]                                      ], # Set context as an object
['context', ObjectType::class, [
                                 "type" => "Link",
                                 "href" => "http://bob.example.org"
                                ]                                      ], # Set context as Link

['current', Collection::class, 'http://example.org/collection'         ], # Set current as a URL
['current', OrderedCollection::class, [
                                        "type" => "Link",
                                        "name" => "Most Recent Items",
                                        "href" => "http://example.org/collection"
                                       ]                               ], # Set current as Link
['current', Collection::class, new CollectionPage()                    ], # Set current as a CollectionPage

['deleted', Tombstone::class, '2016-05-10T00:00:00Z'                   ], # Set deleted as a Datetime
['describes', Profile::class, new ObjectType()                         ], # Set describes as an ObjectType
['describes', Profile::class, new Note()                               ], # Set describes as a Note

['duration', ObjectType::class, 'PT2H'                                 ], # Set duration as short format
['duration', ObjectType::class, 'P5D'                                  ], # Set duration as short format
['duration', Activity::class, 'P5Y0M1DT3H2M12S'                        ], # Set duration as long format

['endTime', ObjectType::class, '2016-05-10T00:00:00Z'                  ], # Set endTime as a Datetime (UTC)
['endTime', ObjectType::class, '2015-01-31T06:00:00-08:00'             ], # Set endTime as a Datetime (TZ)

['endpoints', Person::class, 'http://sally.example.org/endpoints.json' ], # Set endpoints as a string
['endpoints', Person::class, [
                               "proxyUrl" => "http://example.org/proxy.json",
                               "oauthAuthorizationEndpoint" => "http://example.org/oauth.json",
                               "oauthTokenEndpoint" => "http://example.org/oauth/token.json",
                               "provideClientKey" => "http://example.org/provide-client-key.json",
                               "signClientKey" => "http://example.org/sign-client-key.json",
                               "sharedInbox" => "http://example.org/shared-inbox.json"
                              ]                                        ], # Set endpoints as a mapping

['first', Collection::class, 'http://example.org/collection?page=0'    ], # Set first as a URL
['first', OrderedCollection::class, [
                                      "type" => "Link",
                                      "name" => "First Page",
                                      "href" => "http://example.org/collection?page=0"
                                     ]                                 ], # Set first as Link
['followers', Person::class, 
    "https://kenzoishii.example.com/followers.json"                    ], # Set followers as link
['followers', Person::class, new Collection()                          ], # Set followers as collection
['followers', Person::class, new OrderedCollection()                   ], # Set followers as OrderedCollection
['following', Person::class, 
    "https://kenzoishii.example.com/following.json"                    ], # Set following as link
['following', Person::class, new Collection()                          ], # Set following as collection
['following', Person::class, new OrderedCollection()                   ], # Set following as OrderedCollection

['formerType', Tombstone::class, new Note()                            ], # Set formerType as an Note
['formerType', Tombstone::class, ["type" => "Video"]                   ], # Set formerType as an Video array
['formerType', Tombstone::class, Type::create('Video')                 ], # Set formerType as an Video type


['generator', ObjectType::class, new Person()                          ], # Set generator as a Person
['generator', Note::class, ["type" => "Application"]                   ], # Set generator as an Application string
['generator', Note::class, 'http://example.org/generator'              ], # Set generator as URL
['generator', Note::class, [
                             "type" => "Link",
                             "href" => "http://example.org/generator"
                           ]                                           ], # Set generator as Link
['height', Link::class, 42                                             ], # Set height

['href', Link::class, "http://example.org/generator"                   ], # Set href

['hreflang', Link::class, "i-navajo"                                   ], # Set hreflang irregular
['hreflang', Link::class, "en-GB"                                      ], # Set hreflang lang+region
['hreflang', Link::class, "fr"                                         ], # Set hreflang lang
['hreflang', Link::class, "mn-Cyrl-MN"                                 ], # Set hreflang case
['hreflang', Link::class, "mN-cYrL-Mn"                                 ], # Set hreflang icase

['icon', Note::class, [
                        "type"   => "Image",
                        "name"   => "Note icon",
                        "url"    => "http://example.org/note.png",
                      ]                                                ], # Set icon as  an Image
['icon', Note::class, [
                        [
                         "type"    => "Image",
                         "summary" => "Note (16x16)",
                         "url"     => "http://example.org/note1.png",
                        ],
                        [
                         "type"    => "Image",
                         "summary" => "Note (32x32)",
                         "url"     => "http://example.org/note2.png",
                        ]
                      ]                                                ], # Set icon as an array of Image's
['icon', Note::class, [
                        "type" => "Link",
                        "href" => "http://example.org/icon"
                      ]                                                ], # Set icon as Link
['icon', Note::class, "http://example.org/icon"                        ], # Set icon as a string URL
['icon', Note::class, [
                        "href" => "http://example.org/icon",
                        "href" => "http://example.org/icon2"
                      ]                                                ], # Set icon as an array of string URL

['image', Note::class, [
                         "type" => "Image",
                         "name" => "A Cat",
                         "url"  => "http://example.org/cat.png"
                       ]                                               ], # Set image as  an Image
['image', Note::class, [
                         [
                          "type" => "Image",
                          "name" => "Cat 1",
                          "url"  => "http://example.org/cat1.png"
                         ],
                         [
                          "type" => "Image",
                          "name" => "Cat 2",
                          "url"  => "http://example.org/cat2.png"
                         ]
                        ]                                              ], # Set image as an array of Image's
['image', Note::class, [
                         "type" => "Link",
                         "href" => "http://example.org/image"
                       ]                                               ], # Set image as Link

['inbox', Person::class, "http://example.org/name/inbox"               ], # Set inbox as an URL
['inbox', Person::class, new OrderedCollection()                       ], # Set inbox as an OrderedCollection
['inbox', Application::class, new OrderedCollectionPage()              ], # Set inbox as an OrderedCollectionPage

['inReplyTo', ObjectType::class, null                                  ], # Set inReplyTo as a NULL value
['inReplyTo', ObjectType::class, "http://example.org/collection"       ], # Set inReplyTo as URL
['inReplyTo', ObjectType::class, [
                         "type" => "Link",
                         "href" => "http://example.org/image"
                        ]                                              ], # Set inReplyTo as Link
['inReplyTo', ObjectType::class, new ObjectType()                      ], # Set inReplyTo as ObjectType

['instrument', Activity::class, 'https://example.com/bob'              ], # Set instrument as URL
['instrument', Activity::class, [
                                  "type"    => "Person",
                                  "id"      => "http://sally.example.org",
                                  "summary" => "Sally"
                                ]                                      ], # Set instrument as an instrument type, JSON encoded
['instrument', Activity::class, [
                                  "http://joe.example.org",
                                  [
                                    "type" => "Person",
                                    "id"   => "http://sally.example.org",
                                    "name" => "Sally"
                                  ]
                            ]                                          ], # Set instrument as multiple instruments, JSON encoded

['items', Collection::class, []                                        ], # Set items as an empty array
['items', Collection::class, $link                                     ], # Set items as a link
['items', Collection::class, 'http://example.org/collection'           ], # Set items as a URL
['items', Collection::class, [
                                [
                                    "type" =>  "Note",
                                    "name" =>  "Reminder for Going-Away Party"
                                ],
                                [
                                    "type" =>  "Note",
                                    "name" =>  "Meeting 2016-11-17"
                                ]
                            ]                                          ], # Set items as a list, JSON encoded
['items', Collection::class, [$note, $note]                            ], # Set items as a list, Array encoded

['last', Collection::class, 'http://example.org/collection?page=1'     ], # Set last as a URL
['last', OrderedCollection::class, [
                                        "type" => "Link",
                                        "name" => "Last page",
                                        "href" => "http://example.org/collection?page=1"
                                    ]                                  ], # Set last as Link
['last', Collection::class, new CollectionPage()                       ], # Set last as a CollectionPage

['latitude', Place::class, 42                                          ], # Set latitude as an integer
['latitude', Place::class, -42.6                                       ], # Set latitude as a float number

['liked', Person::class, 
    "https://kenzoishii.example.com/liked.json"                        ], # Set liked as link
['liked', Person::class, new Collection()                              ], # Set liked as collection
['liked', Person::class, new OrderedCollection()                       ], # Set liked as OrderedCollection

['location', ObjectType::class, $place                                 ], # Set location with a place
['location', ObjectType::class, $link                                  ], # Set location with a Link
['location', ObjectType::class, "http://example.org/location"          ], # Set location with a URL


['longitude', Place::class, 92                                         ], # Set longitude as an integer
['longitude', Place::class, -92.6                                      ], # Set longitude as a float number

['mediaType', Link::class, "application/pdf"                           ], # Set mediaType on a Link
['mediaType', ObjectType::class, "multipart/form-data"                 ], # Set mediaType on an ObjectType
['mediaType', ObjectType::class, "application/xhtml+xml"               ], # Set mediaType
['mediaType', ObjectType::class, "application/*"                       ], # Set mediaType
['mediaType', ObjectType::class, "application/octet-stream"            ], # Set mediaType
['mediaType', ObjectType::class, "application/vnd.mspowerpoint"        ], # Set mediaType

['name', ObjectType::class, "Bob"                                      ], # Set name with a simple string
['name', ObjectType::class, "Bob 123 !:.,\\/"                          ], # Set name with words, digits and special characters
['name', ObjectType::class, "Bob ;§&~|=[][]*-+/%$^@#\"'"               ], # Set name with words, digits and special characters
['name', Link::class, "Bob ;§&~|=[][]*-+/%$^@#\"'"                     ], # Set name with words, digits and special characters on a Link

['nameMap', Link::class, [
        "en"      => "Bob ;§&~|=[][]*-+/%$^@#\"\'",
        "es"      => "Una nota sencilla",
        "zh-Hans" => "一段简单的笔记"
    ]                                                                  ], # Set nameMap with words, digits and special characters on a Link

['next', CollectionPage::class, [
                            "type" => "Link",
                            "name" => "Next Page",
                            "href" => "http://example.org/collection?page=2"
                        ]                                              ], # Set next as a Link
['next', CollectionPage::class, 'http://example.org/collection?page=2' ], # Set next as a URL
['next', CollectionPage::class, new CollectionPage()                   ], # Set next as a CollectionPage

['object', Activity::class, "http://example.org/object"                ], # Set object as URL
['object', Relationship::class, [
                         "type" => "Link",
                         "href" => "http://example.org/image"
                        ]                                              ], # Set object as Link
['object', Activity::class, new ObjectType()                           ], # Set object as ObjectType
['object', Activity::class, [
                                "http://example.org/posts/1",
                                [
                                    "type"    => "Note",
                                    "name"    => "A simple note",
                                    "content" => "A simple note"
                                ]
                            ]                                          ], # Set object as a collection of object

['oneOf', Question::class, [
                              [
                                "type" => "Note",
                                "name" => "Option A"
                              ],
                              [
                                "type" => "Note",
                                "name" => "Option B"
                              ]
                            ]                                          ], # Set oneOf choices 

['orderedItems', Collection::class, []                                 ], # Set orderedItems as an empty array
['orderedItems', OrderedCollection::class, $link                       ], # Set orderedItems as a link
['orderedItems', OrderedCollection::class, [
                                [
                                    "type" => "Note",
                                    "name" => "Reminder for Going-Away Party"
                                ],
                                [
                                    "type" => "Note",
                                    "name" => "Meeting 2016-11-17"
                                ]
                            ]                                          ], # Set orderedItems as a list, JSON encoded
['orderedItems', OrderedCollection::class, [$note, $note]              ], # Set orderedItems as a list, Array encoded

['origin', Activity::class, "http://example.org/origin"                ], # Set origin as URL
['origin', Like::class, [
                         "type" => "Link",
                         "href" => "http://example.org/image"
                        ]                                              ], # Set origin as Link
['origin', Create::class, new ObjectType()                             ], # Set origin as ObjectType

['outbox', Person::class, new OrderedCollection()                      ], # Set outbox as an OrderedCollection
['outbox', Application::class, new OrderedCollectionPage()             ], # Set outbox as an OrderedCollectionPage

['partOf', CollectionPage::class, "http://example.org/collection"      ], # Set partOf as URL
['partOf', CollectionPage::class, [
                         "type" => "Link",
                         "href" => "http://example.org/image"
                        ]                                              ], # Set partOf as Link
['partOf', CollectionPage::class, new Collection()                     ], # Set partOf as Collection

['preview', Link::class, "http://example.org/collection"               ], # Set preview as URL
['preview', ObjectType::class, [
                         "type" => "Link",
                         "href" => "http://example.org/image"
                        ]                                              ], # Set preview as Link
['preview', ObjectType::class, new ObjectType()                        ], # Set preview as ObjectType

['preferredUsername', Person::class, "My Name"                         ], # Set preferredUsername as a string

['prev', CollectionPage::class, [
                            "type" => "Link",
                            "name" => "Prev Page",
                            "href" => "http://example.org/collection?page=1"
                        ]                                              ], # Set prev as a Link
['prev', CollectionPage::class, 'http://example.org/collection?page=1' ], # Set prev as a URL
['prev', CollectionPage::class, new CollectionPage()                   ], # Set prev as a CollectionPage

['published', ObjectType::class, '2016-05-10T00:00:00Z'                ], # Set published as a Datetime (UTC)
['published', ObjectType::class, '2015-01-31T06:00:00-08:00'           ], # Set published as a Datetime (TZ)

['radius', Place::class, 0                                             ], # Set radius as an integer
['radius', Place::class, 42                                            ], # Set radius as an integer
['radius', Place::class, 42.6                                          ], # Set radius as a float number

['rel', Link::class, ["canonical", "preview"]                          ], # Set rel as an array
['rel', Link::class, "alternate"                                       ], # Set rel as a string

['replies', ObjectType::class, 'http://example.org/collection?page=1'  ], # Set replies as a URL
['replies', ObjectType::class, new Collection()                        ], # Set replies as a Collection
['replies', ObjectType::class, $link                                   ], # Set replies as a Link
['replies', CollectionPage::class, [
                            "type" => "Link",
                            "name" => "Collection of replies",
                            "href" => "http://example.org/replies"
                        ]                                              ], # Set replies as a Link

['result', Activity::class, "http://example.org/result"                ], # Set result as URL
['result', Like::class, [
                         "type" => "Link",
                         "href" => "http://example.org/image"
                        ]                                              ], # Set result as Link
['result', Create::class, new ObjectType()                             ], # Set result as ObjectType

['source', ObjectType::class, [
                                "content" => "I *really* like strawberries!",
                                "mediaType" => "text/markdown"
                            ]                                          ], # Set source as a string object
['source', Note::class, [
                            "content"   => "I *really* like strawberries!",
                            "mediaType" => "text/markdown"
                        ]                                              ], # Set source as an array

['startIndex', OrderedCollectionPage::class, 0                         ], # Set startIndex as 0
['startIndex', OrderedCollectionPage::class, 42                        ], # Set startIndex as 42

['startTime', ObjectType::class, '2016-05-10T00:00:00Z'                ], # Set startTime as a Datetime (UTC)
['startTime', ObjectType::class, '2015-01-31T06:00:00-08:00'           ], # Set startTime as a Datetime (TZ)

['streams', Application::class, []                                     ], # Set streams as array (@todo should be better implemented, an array of Collection)

['subject', Relationship::class, 'http://example.org/collection?page=1'], # Set subject as a URL
['subject', Relationship::class, new ObjectType()                      ], # Set subject as a ObjectType
['subject', Relationship::class, $link                                 ], # Set subject as a Link
['subject', Relationship::class, [
                            "type" => "Link",
                            "name" => "Collection of subject",
                            "href" => "http://example.org/subject"
                         ]                                             ], # Set subject as a Link

['summary', Application::class, 'A simple <em>note</em>'               ], # Set summary as a string
['summaryMap', Application::class, [
                                     "en"      => "A simple <em>note</em>",
                                     "es"      => "Una <em>nota</em> sencilla",
                                     "zh-Hans" => "一段<em>简单的</em>笔记"
                                   ]                                   ], # Set summaryMap as a map

['tag', Note::class, [
                              [
                                 "type" => "Image",
                                 "url"  => "http://example.org/tag"
                              ]
                     ]                                                 ], # Set tag with an ObjectType
['tag', Note::class, [
                              [
                                "type" => "Link",
                                "href" => "http://example.org/tag"
                              ]
                     ]                                                 ], # Set tag with an Link
['tag', Note::class, ["http://example.org/tag"]                        ], # Set tag with an indirect reference
['tag', ObjectType::class, [
                                     [
                                       "type" => "Object",
                                       "url"  => "http://example.org/tag"
                                     ]
                           ]                                           ], # Set tag

['target', Activity::class, 'https://example.com/bob'                  ], # Set target as URL
['target', Activity::class, [
                              "type" => "Person",
                              "id"   => "http://sally.example.org",
                              "summary" => "Sally"
                            ]                                          ], # Set target as an target type, JSON encoded
['target', Activity::class, [
                              "http://joe.example.org",
                              [
                                "type" => "Person",
                                "id"   => "http://sally.example.org",
                                "name" => "Sally"
                              ]
                            ]                                          ], # Set target as multiple targets, JSON encoded

['to', Offer::class, [
                       "http://joe.example.org",
                       [
                          "type" => "Person",
                          "name" => "Bob",
                          "url"  => "http://bob.example.org"
                       ]
                     ]                                                 ], # Set to with an array of mixed URL and persons
['to', Offer::class, []                                                ], # Set to with an empty array

['totalItems', Collection::class, 0                                    ], # Set totalItems as 0
['totalItems', Collection::class, 42                                   ], # Set totalItems as 42

['type', Person::class, "Person"                                       ], # Set type as a string

['units', Place::class, 'miles'                                        ], # Set units as a basic units
['units', Place::class, 'http://example.org/my-units'                  ], # Set units as a xsd:anyURI

['updated', ObjectType::class, '2016-05-10T00:00:00Z'                  ], # Set updated as a Datetime (UTC)
['updated', ObjectType::class, '2015-01-31T06:00:00-08:00'             ], # Set updated as a Datetime (TZ)

['url', Note::class, [
                         "type" => "Link",
                         "href" => "http://example.org/url"
                     ]                                                 ], # Set url as Link
['url', Note::class, [[
                         "type"=> "Link",
                         "href"=> "http://example.org/url"
                      ],
                      "http://example.org/4q-sales-forecast.pdf"]      ], # Set url as an array of Link and URL
['url', Document::class, 'http://example.org/4q-sales-forecast.pdf'    ], # Set url as a URL

['width', Link::class, 42                                              ], # Set width

['id', ObjectType::class, "http://sally.example.org"                   ], # Set an id
		];
	}

/* -------------------------------------------------------------------
 | Exception scenarios
 * -------------------------------------------------------------------*/

	/**
	 * Exception scenarios provider
	 */
	public function getExceptionScenarios()
	{
		# TypeClass, property, value
		return [
['actor', Activity::class, 'https:/example.com/bob'                    ], # Set actor as malformed URL
['actor', Activity::class, 'bob'                                       ], # Set actor as not allowed string
['actor', Activity::class, 42                                          ], # Set actor as not allowed type
['actor', Activity::class, []                                          ], # Set actor as a JSON malformed string
['actor', Activity::class, [
                             "http://joe.example.org",
                             [
                              "type" => "Person",
                              "id" => "http://",
                              "name" => "Sally"
                             ]
                            ]                                          ], # Set actor as multiple actors, JSON encoded, invalid id
['actor', Activity::class, [
                             "http://",
                             [
                              "type" => "Person",
                              "id" => "http://joe.example.org",
                              "name" => "Sally"
                             ]
                            ]                                          ], # Set actor as multiple actors, JSON encoded, invalid indirect link
['accuracy', Place::class, -10                                         ], # Set accuracy with a negative int
['accuracy', Place::class, -0.0000001                                  ], # Set accuracy with a negative float
['accuracy', Place::class, 'A0.0000001'                                ], # Set accuracy with a non numeric value
['accuracy', Place::class, 100.000001                                  ], # Set accuracy with a float value out of range
['altitude', Place::class, '100'                                       ], # Set altitude with a fake int value
['altitude', Place::class, '100.5'                                     ], # Set altitude with a text value
['altitude', Place::class, 'hello'                                     ], # Set altitude with a text value
['altitude', Place::class, []                                          ], # Set altitude with an array
['anyOf', Place::class, []                                             ], # Set anyOf for an inappropriate type
['anyOf', Question::class, []                                          ], # Set anyOf with an array
['anyOf', Question::class, '.'                                         ], # Set anyOf with a string
['anyOf', Question::class, [
                             [
                              "type" => "Note",
                             ],
                             [
                              "type" => "Note",
                              "name" => "Option B"
                             ]
                            ]                                          ], # Set anyOf with malformed choices 
['anyOf', Question::class, [
                             [
                              "type" => "Note",
                              "name" => "Option A"
                             ],
                             [
                              "name" => "Option B"
                             ]
                            ]                                          ], # Set anyOf with malformed choices 
['anyOf', Question::class, [
                             "type" => "Note",
                             "name" => "Option A"
                            ]                                          ], # Set anyOf with malformed choices 
['anyOf', Question::class, [
                             [
                              "type" => "Note",
                              "name" => "Option A"
                             ],
                             [
                              "type" => "Note",
                              "name" => ["Option B"]
                             ]
                            ]                                          ], # Set anyOf with malformed choices	
['attachment', Note::class, [
                              [
                               new \StdClass
                              ]
                             ]                                         ], # Set attachment with a PHP basic object
['attachment', Note::class, [
                              [
                               "type" => "Link",
                               "content" => "This is what he looks like.",
                              ]
                             ]                                         ], # Set attachment with a missing reference
['attributedTo', Image::class, [
                                 [
                                  "type" => "Person"
                                 ]
                                ]                                      ], # Set attributedTo with a missing attribute (Array)
['attributedTo', Image::class, [
                                 "name" => "Sally"
                                ]                                      ], # Set attributedTo with a single malformed type
['attributedTo', Image::class, [
                                 "type" => "Link",
                                ]                                      ], # Set attributedTo with a malformed Link
['attributedTo', Image::class, [
                                 "http://sally.example.org",
                                 [
                                  "type" => "Person",
                                 ]
                                ]                                      ], # Set attributedTo with an array of mixed URL and persons (malformed)
['audience', Image::class, [
                             [
                              "type" => "Person"
                             ]
                            ]                                          ], # Set audience with a missing attribute (Array)
['audience', Image::class, [
                             "name" => "Sally"
                            ]                                          ], # Set audience with a single malformed type
['audience', Image::class, [
                             "type" => "Link"
                            ]                                          ], # Set audience with a malformed Link
['audience', Image::class, [
                             "http://sally.example.org",
                             [
                              "type" => "Person",
                             ]
                            ]                                          ], # Set audience with an array of mixed URL and persons (malformed)
['audience', Image::class, 42                                          ], # Set audience with an integer
['audience', Link::class, ["http://sally.example.org"]                 ], # Set audience with on a bad container (Link)
['bcc', Offer::class, [
                        "http://sally.example.org",
                        [
                         "type" => "Person",
                         "name" => "Sally"
                        ]
                       ]                                               ], # Set bcc with an array of mixed URL and persons (missing url property)
['bcc', Offer::class, [
                        "http://sally.example.org",
                        [
                         "type" => "Person",
                         "name" => "Sally",
                         "url" => "Not an URL"
                        ]
                       ]                                               ], # Set bcc with an array of mixed URL and persons (URL property is not valid)
['bcc', Offer::class, ["Not a valid URL"]                              ], # Set bcc with malformed URL

['bto', Offer::class, [
                        "http://sally.example.org",
                        [
                         "type" => "Person",
                         "name" => "Sally"
                        ]
                       ]                                               ], # Set bto with an array of mixed URL and persons (missing url property)
['bto', Offer::class, [
                        "http://sally.example.org",
                        [
                         "type" => "Person",
                         "name" => "Sally",
                         "url" => "Not an URL"
                        ]
                       ]                                               ], # Set bto with an array of mixed URL and persons (URL property is not valid)
['bto', Offer::class, ["Not a valid URL"]                              ], # Set bto with malformed URL

['cc', Offer::class, [
                       "http://sally.example.org",
                       [
                        "type" => "Person",
                        "name" => "Sally"
                       ]
                      ]                                                ], # Set cc with an array of mixed URL and persons (missing url property)
['cc', Offer::class, [
                       "http://sally.example.org",
                       [
                        "type" => "Person",
                        "name" => "Sally",
                        "url" => "Not an URL"
                       ]
                      ]                                                ], # Set cc with an array of mixed URL and persons (URL property is not valid)
['cc', Offer::class, ["Not a valid URL"]                               ], # Set cc with malformed URL

['closed', Question::class, '2016-05-10 00:00:00Z'                     ], # Set closed as a Datetime (malformed)
['closed', Question::class, '2016-05-32T00:00:00Z'                     ], # Set closed as a Datetime (malformed)
['closed', Question::class, 42                                         ], # Set closed as a integer
['closed', Question::class, 'ob.example.org'                           ], # Set closed as a URL (malformed)
['closed', Question::class, [
                              "type" => "BadType",
                              "name" => "Bob"
                            ]                                          ], # Set closed as a bad type
['closed', Question::class, ["type" => "Link"]                         ], # Set closed as a malformed Link
['closed', ObjectType::class, '2016-05-10T00:00:00Z'                   ], # Set closed as a Datetime but on not allowed type

['content', Note::class, []                                            ], # Set a content as array

['contentMap', Note::class, [
                              "en" => "A <em>simple</em> note",
                              "es" => "Una nota <em>sencilla</em>",
                              1 => "一段<em>简单的</em>笔记"
                             ]                                         ], # Set a content map (bad key)

['contentMap', Note::class, [ "A <em>simple</em> note"]                ], # Set a content map (bad key)
['contentMap', Note::class, 'A <em>simple</em> note'                   ], # Set a content map (bad format, string)
['contentMap', Note::class, 42                                         ], # Set a content map (bad format, integer)

['context', ObjectType::class, '1'                                     ], # Set a number as context
['context', ObjectType::class, []                                      ], # Set an array as context
['context', ObjectType::class, [
                                 "type" => "Link"
                                ]                                      ], # Set context as a malformed Link

['current', ObjectType::class, 'http://example.org/collection'         ], # Set current as a URL for a class which is not a subclass of Collection
['current', Collection::class, 'http:/example.org/collection'          ], # Set current as a malformed URL
['current', OrderedCollection::class, [
                                        "type" => "Link",
                                        "name" => "Most Recent Items"
                                      ]                                ], # Set current as Link (malformed)
['current', Collection::class, 42                                      ], # Set current as a bad type value

['deleted', Tombstone::class, '2016-05-10 00:00:00Z'                   ], # Set deleted as a bad Datetime
['deleted', ObjectType::class, '2016-05-10T00:00:00Z'                  ], # Set deleted as a Datetime on a bad Type
['deleted', Tombstone::class, []                                       ], # Set deleted as an array
['deleted', Tombstone::class, 42                                       ], # Set deleted as an integer

['describes', Profile::class, 42                                       ], # Set describes as an integer
['describes', ObjectType::class, 42                                    ], # Set describes on a bad type

['duration', Link::class, 'PT2H'                                       ], # Set duration on a bad type
['duration', ObjectType::class, 'P5DD'                                 ], # Set duration as malformed short format
['duration', Activity::class, 'PY0M1DT3H2M12S'                         ], # Set duration as malformed format
['duration', Activity::class, new Link()                               ], # Set duration as unallowed format

['endTime', ObjectType::class, '2016-05-10 00:00:00Z'                  ], # Set endTime as a bad Datetime
['endTime', Link::class, '2016-05-10 00:00:00Z'                        ], # Set endTime on a bad type
['endTime', ObjectType::class, new ObjectType()                        ], # Set endTime as a bad type

['endpoints', Person::class, 'htt://sally.example.org/endpoints.json'  ], # Set endpoints as a bad url
['endpoints', Person::class, 42                                        ], # Set endpoints with a bad type value
['endpoints', Activity::class,'http://sally.example.org/endpoints.json'], # Set endpoints on a bad type
['endpoints', Person::class, [
                               "proxyUrl" => "http://example.org/proxy.json",
                               "oauthAuthorizationEndpoint" => "http://example.org/oauth.json",
                               "oauthTokenEndpoint" => "http://example.org/oauth/token.json",
                               "provideClientKey" => "http://example.org/provide-client-key.json",
                               "signClientKey" => "htp://example.org/sign-client-key.json",
                               "sharedInbox" => "http://example.org/shared-inbox.json"
                              ]                                        ], # Set endpoints as a mapping with a malformed URL
['endpoints', Person::class, [["http://example.org/proxy.json"]]       ], # Set endpoints as a mapping with a malformed key

['first', ObjectType::class, 'http://example.org/collection?page=0'    ], # Set first as a URL for a class which is not a subclass of Collection
['first', Collection::class, 'http:/example.org/collection?page=0'     ], # Set first as a malformed URL
['first', OrderedCollection::class, [
                                      "type" => "Link",
                                      "summary" => "First page"
                                     ]                                 ], # Set first as Link (malformed)

['first', Collection::class, 42                                        ], # Set first as a bad type value

['followers', Activity::class, new Collection()                        ], # Set followers on a bad container (must be an actor)
['followers', Person::class, new Activity()                            ], # Set followers as a bad type (must be a Collection or an OrderedCollection)
['followers', Person::class, 'http:/example.org/followers'             ], # Set followers as a malformed URL

['following', Activity::class, new Collection()                        ], # Set following on a bad container (must be an actor)
['following', Person::class, new Activity()                            ], # Set following as a bad type (must be a Collection or an OrderedCollection)
['following', Person::class, 'http:/example.org/following'             ], # Set following as a malformed URL

['formerType', Tombstone::class, 'PoorString'                          ], # Set formerType as a string
['formerType', ObjectType::class, ["type" => "Person"]                 ], # Set formerType on a bad Type
['formerType', Tombstone::class, 42                                    ], # Set formerType as an integer

['generator', Note::class, ["type" => "Activity"]                      ], # Set generator as an activity
['generator', ObjectType::class, '2016-05-10T00:00:00Z'                ], # Set generator as a Datetime on a bad Type
['generator', Tombstone::class, 42                                     ], # Set generator as an integer
['generator', Link::class, 'http://example.org/generator'              ], # Set generator on a bad type
['generator', ObjectType::class, 'htp://example.org/generator'         ], # Set generator with a bad URL
['generator', Note::class, [
                             "type" => "Link",
                             "href" => "htp://example.org/generator"
                           ]                                           ], # Set generator as a malformed Link

['height', ObjectType::class, 42                                       ], # Set height on a bad type
['height', Link::class, 42.5                                           ], # Set height with a bad type
['height', Link::class, 'cat'                                          ], # Set height with a bad type
['height', Link::class, -42                                            ], # Set height with an out of range value

['href', ObjectType::class, "http://example.org/generator"             ], # Set href on a bad type
['href', Link::class, "htp://example.org/generator"                    ], # Set href with a bad URL
['href', Link::class, new Activity()                                   ], # Set href with a bad type

['hreflang', Link::class, "i-navajoK"                                  ], # Set hreflang bad irregular
['hreflang', Activity::class, "en-GB"                                  ], # Set hreflang on a bad type


['icon', Note::class, [
                        "type" => "Imag",
                        "name" => "Note icon",
                        "url" => "http://example.org/note.png",
                        "width"=> 16,
                        "height"=> 16
                      ]                                                ], # Set icon as a bad type
['icon', Note::class, [
                        [
                         "type" => "Imag",
                         "summary" => "Note (16x16)",
                         "url" => "http://example.org/note1.png",
                         "width"=> 16,
                         "height"=> 16
                        ],
                        [
                         "type" => "Image",
                         "summary" => "Note (32x32)",
                         "url" => "http://example.org/note2.png",
                         "width"=> 32,
                         "height"=> 32
                        ]
                       ]                                               ], # Set icon as an array of Image's
['icon', Note::class, [
                        "type" => "Link"
                       ]                                               ], # Set icon as Link
['icon', Note::class, "htp://example.org/icon"                         ], # Set icon as a malformed URL
['icon', Note::class, [
                        "href" => "http://example.org/icon",
                        "href" => "htt://example.org/icon2"
                      ]                                                ], # Set icon as an array of string URL (malformed last one)

['image', Note::class, [
                         "type" => "Imag",
                         "name" => "Note image",
                         "url" => "http://example.org/note.png"
                        ]                                              ], # Set image as a bad type
['image', Note::class, [
                         [
                          "type" => "Imag",
                          "summary" => "Note image",
                          "url" => "http://example.org/note1.png"
                         ],
                         [
                          "type" => "Image",
                          "summary" => "A cat",
                          "url" => "http://example.org/note2.png"
                         ]
                        ]                                              ], # Set image as an array of Image's (bad type)
['image', Note::class, [
                         "type" => "Link"
                       ]                                               ], # Set image as Link (malformed)

['inbox', Person::class, "htp://example.org/name/inbox"                ], # Set inbox as an URL (malformed)
['inbox', Activity::class, new OrderedCollection()                     ], # Set inbox on a bad type (Activity)
['inbox', Application::class, new CollectionPage()                     ], # Set inbox as a bad type (Must be an ordered Type)
['inbox', Application::class, 'string'                                 ], # Set inbox as a bad type (Must be a valid object)

['inReplyTo', CollectionPage::class, []                                ], # Set inReplyTo as a bad type
['inReplyTo', ObjectType::class, "htp://example.org"                   ], # Set inReplyTo as a bad URl value
['inReplyTo', Link::class, "http://example.org"                        ], # Set inReplyTo on a bad type (Link)

['instrument', Activity::class, 'https:/example.com/bob'               ], # Set instrument as malformed URL
['instrument', Activity::class, 'bob'                                  ], # Set instrument as not allowed string
['instrument', Activity::class, 42                                     ], # Set instrument as not allowed type
['instrument', Activity::class, []                                     ], # Set instrument as a JSON malformed string
['instrument', Activity::class, [
                             "http://joe.example.org",
                             [
                              "type" => "Person",
                              "id" => "http://",
                              "name" => "Sally"
                             ]
                            ]                                          ], # Set instrument as multiple instruments, JSON encoded, invalid id
['instrument', Activity::class, [
                             "http://",
                             [
                              "type" => "Person",
                              "id" => "http://joe.example.org",
                              "name" => "Sally"
                             ]
                            ]                                          ], # Set instrument as multiple instruments, JSON encoded, invalid indirect link

['items', Collection::class, 42                                        ], # Set items as an integer (bad type)
['items', Activity::class, "A string collection"                       ], # Set items as a string (bad type)
['items', Activity::class, [
                             "type" => "Link",
                             "href" => "http://example.org/items"
                           ]                                           ], # Set items on a bad type
['items', Collection::class, [
                             "type" => "Note",
                             "name" => "It\'s a note"
                             ]                                         ], # Set items as a bad type (must be a list)
['items', Collection::class, [[
                             "name" => "It\'s a note"
                            ]]                                         ], # Set items as a malformed list (Item has no type)

['last', Activity::class, [
                            "type" => "Link",
                            "name" => "last Page",
                            "href" => "htp://example.org/collection?page=2"
                        ]                                              ], # Set last on a bad type
['last', CollectionPage::class, [
                            "type" => "Link",
                            "name" => "last Page",
                            "href" => "htp://example.org/collection?page=2"
                        ]                                              ], # Set last as a malformed Link
['last', CollectionPage::class, 'htp://example.org/collection?page=2'  ], # Set last as a malformed URL
['last', CollectionPage::class, new Collection()                       ], # Set last as a bad type

['latitude', Place::class, -142                                        ], # Set latitude as an out of range value
['latitude', Place::class, 'Bad Type'                                  ], # Set latitude as a bad type
['latitude', ObjectType::class, 42                                     ], # Set latitude on a bad type

['liked', Activity::class, new Collection()                            ], # Set liked on a bad container (must be an actor)
['liked', Person::class, new Activity()                                ], # Set liked as a bad type (must be a Collection or an OrderedCollection)
['liked', Person::class, 'http:/example.org/liked'                     ], # Set liked as a malformed URL

['location', Link::class, "http://example.org/location"                ], # Set location on a bad type
['location', ObjectType::class, "htp://example.org/location"           ], # Set location with a malformed URL

['longitude', Place::class, -182                                       ], # Set longitude as an out of range value
['longitude', Place::class, 'Bad Type'                                 ], # Set longitude as a bad type
['longitude', ObjectType::class, 42                                    ], # Set longitude on a bad type

['mediaType', ObjectType::class, "application/audio-*"                 ], # Set mediaType
['mediaType', ObjectType::class, "application.octet-stream"            ], # Set mediaType
['mediaType', ObjectType::class, "application+vnd.mspowerpoint"        ], # Set mediaType

['name', Link::class, "Bob <span></span>"                              ], # Set name with illegal characters (HTML)
['name', ObjectType::class, "Bob <script></script>"                    ], # Set name with illegal characters (HTML)

['nameMap', Link::class, [
        "en" => "Bob ;§&~|=[][]*-+/%$^@#\"\'",
        "es" => "Una nota sencilla",
        "zh-Hans" => "<script></script>"
    ]                                                                  ], # Set nameMap with an illegal string (HTML)

['nameMap', Link::class, [
        "abcdefghijkl" => "Bob ;§&~|=[][]*-+/%$^@#\"\'",
        "es" => "Una nota sencilla"
    ]                                                                  ], # Set nameMap with an illegal key (Non valid BCP47)

['next', Collection::class, [
                            "type" => "Link",
                            "name" => "Next Page",
                            "href" => "http://example.org/collection?page=2"
                        ]                                              ], # Set next on a bad type
['next', CollectionPage::class, [
                            "type" => "Link",
                            "name" => "Next Page",
                            "href" => "htp://example.org/collection?page=2"
                        ]                                              ], # Set next as a malformed Link
['next', CollectionPage::class, 'htp://example.org/collection?page=2'  ], # Set next as a malformed URL
['next', CollectionPage::class, new Collection()                       ], # Set next as a bad type
['next', CollectionPage::class, 42                                     ], # Set next as a bad type (int)

['object', ObjectType::class, 'http://example.org/object'              ], # Set object on a bad type
['object', Relationship::class, []                                     ], # Set object as a bad type
['object', Relationship::class, 42                                     ], # Set object as a bad type (int)
['object', Activity::class, "htp://example.org"                        ], # Set object as a bad URL
['object', Relationship::class, [['key' => 'o']]                       ], # Set object as a bad list type


['oneOf', Place::class, []                                             ], # Set oneOf for an inappropriate type
['oneOf', Question::class, []                                          ], # Set oneOf with an array
['oneOf', Question::class, [
                             [
                              "type" => "Note",
                             ],
                             [
                              "type" => "Note",
                              "name" => "Option B"
                             ]
                            ]                                          ], # Set oneOf with malformed choices 
['oneOf', Question::class, [
                             [
                              "type" => "Note",
                              "name" => "Option A"
                             ],
                             [
                              "name" => "Option B"
                             ]
                            ]                                          ], # Set oneOf with malformed choices 
['oneOf', Question::class, [
                             "type" => "Note",
                             "name" => "Option A"
                            ]                                          ], # Set oneOf with malformed choices 
['oneOf', Question::class, [
                             [
                              "type" => "Note",
                              "name" => "Option A"
                             ],
                             [
                              "type" => "Note",
                              "name" => ["Option B"]
                             ]
                            ]                                          ], # Set oneOf with malformed choices

['orderedItems', Activity::class, [
                             "type" => "Link",
                             "href" => "http://example.org/orderedItems"
                            ]                                          ], # Set orderedItems on a bad type
['orderedItems', OrderedCollection::class, [
                             "type" => "Note",
                             "name" => "It's a note"
                            ]                                          ], # Set orderedItems as a bad type (must be a list)
['orderedItems', OrderedCollection::class, [[
                             "name" => "It's a note"
                            ]]                                         ], # Set orderedItems as a malformed list (Item has no type)

['origin', Collection::class, []                                       ], # Set origin on a bad type
['origin', Activity::class, "htp://example.org"                        ], # Set origin as a bad URL
['origin', Activity::class, 42                                         ], # Set origin as a bad type (int)

['outbox', Activity::class, new OrderedCollection()                    ], # Set outbox on a bad type (Activity)
['outbox', Application::class, new CollectionPage()                    ], # Set outbox as a bad type (Must be an ordered Type)
['outbox', Application::class, 'string'                                ], # Set outbox as a bad type (Must be a valid object)

['partOf', CollectionPage::class, "htp://example.org/collection"       ], # Set partOf as a bad URL
['partOf', CollectionPage::class, [
                         "type" => "Lin",
                         "href" => "http://example.org/image"
                        ]                                              ], # Set partOf as as bad Link
['partOf', Collection::class, new Collection()                         ], # Set partOf on a bad type
['partOf', CollectionPage::class, []                                   ], # Set partOf as a bad type

['preview', CollectionPage::class, []                                  ], # Set preview as a bad type
['preview', ObjectType::class, "htp://example.org"                     ], # Set preview as a bad URL

['preferredUsername', Activity::class, 'My name'                       ], # Set preferredUsername on a bad type (Activity)
['preferredUsername', Application::class, new OrderedCollection()      ], # Set preferredUsername as a bad type (OrderedCollection)

['prev', Collection::class, [
                            "type" => "Link",
                            "name" => "prev Page",
                            "href" => "http://example.org/collection?page=2"
                        ]                                              ], # Set prev on a bad type
['prev', CollectionPage::class, [
                            "type" => "Link",
                            "name" => "prev Page",
                            "href" => "htp://example.org/collection?page=2"
                        ]                                              ], # Set prev as a malformed Link
['prev', CollectionPage::class, 'htp://example.org/collection?page=2'  ], # Set prev as a malformed URL
['prev', CollectionPage::class, new Collection()                       ], # Set prev as a bad type

['published', ObjectType::class, '2016-05-10 00:00:00Z'                ], # Set published as a bad Datetime
['published', Link::class, '2016-05-10 00:00:00Z'                      ], # Set published on a bad type
['published', ObjectType::class, new ObjectType()                      ], # Set published as a bad type

['radius', Place::class, -182                                          ], # Set radius as an out of range value
['radius', Place::class, 'Bad Type'                                    ], # Set radius as a bad type
['radius', ObjectType::class, 42                                       ], # Set radius on a bad type

['rel', Video::class, ["canonical", "preview"]                         ], # Set rel on a bad type
['rel', Link::class, new Note()                                        ], # Set rel as a bad type
['rel', Link::class, "hello "                                          ], # Set rel as an illegal chain " "
['rel', Link::class, "hello,"                                          ], # Set rel as an illegal chain ,
['rel', Link::class, "hello\n"                                         ], # Set rel as an illegal chain \n
['rel', Link::class, "hello\r"                                         ], # Set rel as an illegal chain \r

['replies', ObjectType::class, 'htp://example.org/collection?page=1'   ], # Set replies as a bad URL
['replies', ObjectType::class, new ObjectType()                        ], # Set replies as a bad type
['replies', ObjectType::class, 42                                      ], # Set replies as a bad type (int)
['replies', Link::class, new Link()                                    ], # Set replies on a bad type (Link)
['replies', CollectionPage::class, [
                            "type" => "Object",
                            "name" => "Collection of replies",
                            "href" => "http://example.org/replies"
                        ]                                              ], # Set prev as a text Object (bad format)

['result', Collection::class, []                                       ], # Set result on a bad type
['result', Activity::class, "htp://example.org"                        ], # Set result as a bad URL

['source', Link::class, [
                                "content" => "I *really* like strawberries!",
                                "mediaType" => "text/markdown"
                        ]                                              ], # Set source on a bad type
['source', ObjectType::class, [
                                "mediaType" => "text/markdown"
                              ]                                        ], # Set source with an incomplete object
['source', Note::class, [
                            "content"   => "I *really* like strawberries!",
                        ]                                              ], # Set source with an incomplete object
['source', Note::class, 1                                              ], # Set source with a bad type (int)

['startIndex', ObjectType::class, 0                                    ], # Set startIndex on a bad type
['startIndex', OrderedCollectionPage::class, 42.5                      ], # Set startIndex as a bad type
['startIndex', OrderedCollectionPage::class, -41                       ], # Set startIndex as an out of range value

['startTime', ObjectType::class, '2016-05-10 00:00:00Z'                ], # Set startTime as a bad Datetime
['startTime', Link::class, '2016-05-10 00:00:00Z'                      ], # Set startTime on a bad type
['startTime', ObjectType::class, new ObjectType()                      ], # Set startTime as a bad type

['streams', Application::class, 'collection'                           ], # Set streams as a bad type
['streams', ObjectType::class, []                                      ], # Set streams on a bad type (Must be an Actor)

['subject', Relationship::class, 'htp://example.org/collection?page=1' ], # Set subject as a bad URL
['subject', Relationship::class, new \StdClass()                       ], # Set subject as a bad type (object)
['subject', Relationship::class, 42                                    ], # Set subject as a bad type (int)
['subject', Person::class, new ObjectType()                            ], # Set subject on a bad type
['subject', Relationship::class, [
                            "type" => "Link",
                            "name" => "Collection of subject",
                            "href" => "htp://example.org/subject"
                        ]                                              ], # Set subject as a malformed Link

['summary', Link::class, 'A simple <em>note</em>'                      ], # Set summary on a bad type
['summary', ObjectType::class, new Note()                              ], # Set summary as a bad type
['summaryMap', Link::class, [
                              "en" => "A simple <em>note</em>",
                              "es" => "Una <em>nota</em> sencilla",
                              "zh-Hans" => "一段<em>简单的</em>笔记"
                             ]                                         ], # Set summaryMap on a bad type
['summaryMap', Note::class, 'A simple <em>note</em>'                   ], # Set summaryMap on a bad type

['tag', Note::class, [
                              [
                               "content" => "This is what he looks like.",
                              ]
                             ]                                         ], # Set tag with a missing type
['tag', Note::class, [
                              [
                               "type" => "Link",
                               "content" => "This is what he looks like.",
                              ]
                             ]                                         ], # Set tag with a missing reference

['target', Activity::class, 'https:/example.com/bob'                   ], # Set target as malformed URL
['target', Activity::class, 'bob'                                      ], # Set target as not allowed string
['target', Activity::class, 42                                         ], # Set target as not allowed type
['target', Activity::class, []                                         ], # Set target as a JSON malformed string
['target', Activity::class, [
                             "http://joe.example.org",
                             [
                              "type" => "Person",
                              "id" => "http://",
                              "name" => "Sally"
                             ]
                            ]                                          ], # Set target as multiple targets, JSON encoded, invalid id
['target', Activity::class, [
                             "http://",
                             [
                              "type" => "Person",
                              "id" => "http://joe.example.org",
                              "name" => "Sally"
                             ]
                            ]                                          ], # Set target as multiple targets, JSON encoded, invalid indirect link

['to', Offer::class, [
                        "http://sally.example.org",
                        [
                         "type" => "Person",
                         "name" => "Sally"
                        ]
                       ]                                               ], # Set to with an array of mixed URL and persons (missing url property)
['to', Offer::class, [
                        "http://sally.example.org",
                        [
                         "type" => "Person",
                         "name" => "Sally",
                         "url" => "Not an URL"
                        ]
                       ]                                               ], # Set to with an array of mixed URL and persons (URL property is not valid)
['to', Offer::class, ["Not a valid URL"]                               ], # Set to with malformed URL

['totalItems', ObjectType::class, 42                                   ], # Set totalItems on a bad type
['totalItems', Collection::class, 42.5                                 ], # Set totalItems with a bad type
['totalItems', Collection::class, 'cat'                                ], # Set totalItems with a bad type
['totalItems', Collection::class, -42                                  ], # Set totalItems with an out of range value

['type', ObjectType::class, ""                                         ], # Set type as an empty string
['type', ObjectType::class, null                                       ], # Set type as a null value
['type', ObjectType::class, 42                                         ], # Set type as an integer
['type', ObjectType::class, []                                         ], # Set type as an array

['units', Place::class, 'mile'                                         ], # Set units as a bad units
['units', Place::class, 'htp://example.org/my-units'                   ], # Set units as a bad xsd:anyURI
['units', ObjectType::class, 'miles'                                   ], # Set units on a bad type

['updated', ObjectType::class, '2016-05-10 00:00:00Z'                  ], # Set updated as a bad Datetime
['updated', Link::class, '2016-05-10 00:00:00Z'                        ], # Set updated on a bad type
['updated', ObjectType::class, new ObjectType()                        ], # Set updated as a bad type

['url', Link::class, 'http://example.org/4q-sales-forecast.pdf'        ], # Set url on a bad type
['url', Note::class, [
                         "type" => "Link"
                      ]                                                ], # Set url as malformed Link
['url', Note::class, [
                         "type" => "Link",
                         "href"=> "htp://example.org/url"
                      ]                                                ], # Set url as malformed Link
['url', Note::class, [[
                         "type"=> "Link",
                         "href"=> "http://example.org/url"
                      ],
                      "htp://example.org/4q-sales-forecast.pdf"]       ], # Set url as an array of Link and URL (malformed URL)
['url', Document::class, 'http:/example.org/4q-sales-forecast.pdf'     ], # Set url as a malformed  URL

['width', ObjectType::class, 42                                        ], # Set width on a bad type
['width', Link::class, 42.5                                            ], # Set width with a bad type
['width', Link::class, 'cat'                                           ], # Set width with a bad type
['width', Link::class, -42                                             ], # Set width with an out of range value

['id', ObjectType::class, '1'                                          ], # Set a number as id   (should pass @todo type resolver)
['id', ObjectType::class, []                                           ], # Set an array as id
		];
	}

	/**
	 * Check that all core objects have a correct type property.
	 * It checks that getter is working well too.
	 * 
	 * @dataProvider      getValidAttributesScenarios
	 */
	public function testValidAttributesScenarios($attr, $type, $value)
	{
		$object = new $type();
		$object->{$attr} = $value;
        
        // Cast $value
        if (is_array($value)) {
            if (isset($value['type'])) {
                $value = Type::create($value);
            } elseif (is_int(key($value))) {
                $value = array_map(function($value) {
                        return is_array($value) && isset($value['type'])
                            ? Type::create($value)
                            : $value;
                    },
                    $value
                );
            } else {
                //$this->{$name} = $value;
            }
        }

		$this->assertEquals($value, $object->{$attr});
	}
	
	/**
	 * @dataProvider      getExceptionScenarios
	 * @expectedException \Exception
	 */
	public function testExceptionScenarios($attr, $type, $value)
	{
		$object = new $type();
		$object->{$attr} = $value;
	}

	/**
	 * Validator validate() method MUST receive an object as third parameter
	 * 
	 * @expectedException \Exception
	 */
	public function testValidatorValidateContainer()
	{	
		Validator::validate('property', 'value', 'NotAnObject');
	}


	/**
	 * Validator add method MUST receive an object that implements
	 * \ActivityPhp\Type\ValidatorInterface interface
	 * 
	 * @expectedException \Exception
	 */
	public function testValidatorAddNotValidCustomValidator()
	{
		Validator::add('custom', new class {
			public function validate($value) {
				return true;
			}
		});
	}
}
