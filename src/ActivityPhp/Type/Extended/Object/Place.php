<?php

declare(strict_types=1);

/*
 * This file is part of the ActivityPhp package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPhp\Type\Extended\Object;

use ActivityPhp\Type\Core\ObjectType;

/**
 * \ActivityPhp\Type\Extended\Object\Place is an implementation of
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
     * The altitude of a place.
     * The measurement units is indicated using the units property.
     * If units is not specified, the default is assumed to be "m"
     * indicating meters.
     *
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-altitude
     *
     * @var float|null
     */
    protected $altitude;

    /**
     * The latitude of a place.
     *
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-latitude
     *
     * @var float|int|null
     */
    protected $latitude;

    /**
     * The longitude of a place.
     *
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-longitude
     *
     * @var float|int|null
     */
    protected $longitude;

    /**
     * The radius from the given latitude and longitude for a Place.
     * The units is expressed by the units property.
     * If units is not specified, the default is assumed to be "m"
     * indicating "meters".
     *
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-radius
     *
     * @var float|int|null
     */
    protected $radius;

    /**
     * Specifies the measurement units for the radius and altitude
     * properties on a Place object.
     * If not specified, the default is assumed to be "m" for "meters".
     *
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-units
     *
     * "cm" | " feet" | " inches" | " km" | " m" | " miles" | xsd:anyURI
     *
     * @var string
     */
    protected $units;
}
