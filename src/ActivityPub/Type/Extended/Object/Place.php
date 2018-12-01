<?php

/*
 * This file is part of the ActivityPub package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPub\Type\Extended\Object;

use ActivityPub\Type\Core\ObjectType;

/**
 * \ActivityPub\Type\Extended\Object\Place is an implementation of 
 * one of the Activity Streams Extended Types.
 * 
 * Represents a logical or physical location.
 * 
 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-place
 */ 
class Place extends ObjectType
{
    /**
     * @var string
     */
    protected $type = 'Place';

    /**
     * Indicates the accuracy of position coordinates on a Place 
     * objects. Expressed in properties of percentage.
     * e.g. "94.0" means "94.0% accurate".
     * 
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-accuracy
     * 
     * @var float|null
     */
    protected $accuracy;

    /**
     * @var float|null
     */
    protected $altitude;

    /**
     * @var float|null
     */
    protected $latitude;

    /**
     * @var float|null
     */
    protected $longitude;

    /**
     * @var float|null
     */
    protected $radius;

    /**
     * "cm" | " feet" | " inches" | " km" | " m" | " miles" | xsd:anyURI
     *
     * @var string
     */
    protected $units;
}
