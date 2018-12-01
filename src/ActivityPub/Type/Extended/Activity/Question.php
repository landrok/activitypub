<?php

/*
 * This file is part of the ActivityPub package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPub\Type\Extended\Activity;

use ActivityPub\Type\Core\IntransitiveActivity;

/**
 * \ActivityPub\Type\Extended\Activity\Question is an implementation of 
 * one of the Activity Streams Extended Types.
 *
 * Represents a question being asked. Question objects are an extension 
 * of IntransitiveActivity. That is, the Question object is an Activity,
 * but the direct object is the question itself and therefore it would 
 * not contain an object property.
 * 
 * Either of the anyOf and oneOf properties MAY be used to express 
 * possible answers, but a Question object MUST NOT have both properties 
 *
 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-move
 */
class Question extends IntransitiveActivity
{
    /**
     * @var string
     */
    protected $type = 'Question';

    /**
     * An exclusive option for a Question
     * 
     * @var \ActivityPub\Type\Core\ObjectType
     * 	   |\ActivityPub\Type\Core\Link
     *     |null
     * 
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-oneof
     */
    protected $oneOf;

	/**
	 * An inclusive option for a Question.
	 *
     * @var \ActivityPub\Type\Core\ObjectType
     * 	   |\ActivityPub\Type\Core\Link
     *     |null
     *
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-anyof
     */
    protected $anyOf;

	/**
	 * Indicates that a question has been closed, and answers are no 
	 * longer accepted. 
	 * 
     * @var \ActivityPub\Type\Core\ObjectType
     * 	   |\ActivityPub\Type\Core\Link
     * 	   |\DateTime
     *     |bool
     *     |null
     * 
     * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-closed
     */
    protected $closed;
}
