<?php

/*
 * This file is part of the ActivityPhp package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPhp\Type;

use ActivityPhp\Type\Core\ObjectType;
use Exception;

/**
 * \ActivityPhp\Type\TypeResolver is an abstract class for
 * resolving class names called by their short names (AS types).
 */
abstract class TypeResolver
{
    /**
     * A list of core types
     * 
     * @var array
     */
    protected static $coreTypes = [
        'Activity', 'Collection', 'CollectionPage', 
        'IntransitiveActivity', 'Link', 'ObjectType',
        'OrderedCollection', 'OrderedCollectionPage',
        'Object'
    ];

    /**
     * A list of actor types
     * 
     * @var array
     */
    protected static $actorTypes = [
        'Application', 'Group', 'Organization', 'Person', 'Service'
    ];

    /**
     * A list of activity types
     * 
     * @var array
     */
    protected static $activityTypes = [
        'Accept', 'Add', 'Announce', 'Arrive', 'Block', 
        'Create', 'Delete', 'Dislike', 'Flag', 'Follow',
        'Ignore', 'Invite', 'Join', 'Leave', 'Like', 'Listen',
        'Move',  'Offer', 'Question', 'Read', 'Reject', 'Remove', 
        'TentativeAccept', 'TentativeReject', 'Travel', 'Undo', 
        'Update', 'View', 
    ];

    /**
     * A list of object types
     * 
     * @var array
     */
    protected static $objectTypes = [
        'Article', 'Audio', 'Document', 'Event', 'Image', 
        'Mention', 'Note', 'Page', 'Place', 'Profile', 
        'Relationship', 'Tombstone', 'Video',
    ];

    /**
     * A list of custom types
     * 
     * @var array
     */
    protected static $customTypes = [];

    /**
     * A list of dialect types
     * 
     * @var array
     */
    protected static $dialectTypes = [];

    /**
     * Add a custom type definition in the pool.
     * 
     * @param  string $name A short name.
     * @param  string $class Fully qualified class name.
     * @throws \Exception if class does not exist
     */
    public static function addCustomType(string $name, string $class)
    {
        if (!class_exists($class)) {
            throw new Exception(
                sprintf(
                    'Class "%s" is not defined',
                    $class
                )
            );
        }

        self::$customTypes[$name] = $class;
    }

    /**
     * Get namespaced class for a given short type
     * 
     * @param  string $type
     * @return string Related namespace
     * @throw  \Exception if a namespace was not found.
     */
    public static function getClass($type)
    {
        $ns = __NAMESPACE__;

        if ($type == 'Object') {
            $type .= 'Type';
        }

        switch($type) {
            // Custom dialect types
            case in_array($type, self::$dialectTypes):
                $class = new ObjectType();
                $class->type = $type;
                return $class;
            // Custom classes by facades
            case isset(self::$customTypes[$type]):
                return self::$customTypes[$type];
            case in_array($type, self::$coreTypes):
                $ns .= '\Core';
                break;
            case in_array($type, self::$activityTypes):
                $ns .= '\Extended\Activity';
                break;
            case in_array($type, self::$actorTypes):
                $ns .= '\Extended\Actor';
                break;
            case in_array($type, self::$objectTypes):
                $ns .= '\Extended\Object';
                break;
            default:
                throw new Exception("Undefined scope for type '$type'");
                break;
        }

        return $ns . '\\' . $type;
    }

    /**
     * Validate an object pool type with type attribute
     * 
     * @param  object $item
     * @param  string $poolname An expected pool name
     * @return bool
     */
    public static function isScope($item, string $poolname = 'all')
    {
        if (!is_object($item)
            || !isset($item->type)
            || !is_string($item->type)
        ) {
            return false;
        }

        switch (strtolower($poolname)) {
            case 'all':
                return self::exists($item->type);
            case 'actor':
                return in_array($item->type, self::$actorTypes);
        }

        return false;
    }

    /**
     * Verify that a type exists
     * 
     * @param  string $name
     * @return bool
     */
    public static function exists(string $name)
    {
        return in_array(
            $name,
            array_merge(
                self::$coreTypes,
                self::$activityTypes,
                self::$actorTypes,
                self::$objectTypes,
                self::$dialectTypes
            )
        );        
    }

    /**
     * Define a new dialect type
     * 
     * @param  string $name
     */
    public static function addDialectType(string $name)
    {
        if (!self::exists($name)) {
            array_push(self::$dialectTypes, $name);
        }
    }

    /**
     * Remove a dialect type
     * 
     * @param  string $name
     */
    public static function removeDialectType(string $name)
    {
        if (self::exists($name)) {
            foreach (self::$dialectTypes as $key => $value) {
                if ($name == $value) {
                    array_splice(self::$dialectTypes, $key, 1);
                    return;
                }
            }
        }
    }
}
