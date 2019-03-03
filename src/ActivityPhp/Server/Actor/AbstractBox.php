<?php

/*
 * This file is part of the ActivityPhp package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPhp\Server\Actor;

use ActivityPhp\Server;
use ActivityPhp\Server\Actor;
use ActivityPhp\Type;
use ActivityPhp\Type\AbstractObject;
use Exception;

/**
 * A base class for server-side box
 */ 
abstract class AbstractBox
{
    /**
     * Maximum items to returns while fetching data
     */
    const MAX_ITEMS = 100;

    /**
     * @var \ActivityPhp\Server
     */
    protected $server;

    /**
     * @var \ActivityPhp\Server\Actor
     */
    protected $actor;

    /**
     * A box definition
     *
     * @var \ActivityPhp\Type\Core\OrderedCollection
     */
    protected $orderedCollection;

    /**
     * Box constructor
     * 
     * @param  \ActivityPhp\Server\Actor $actor
     * @param  \ActivityPhp\Server $server
     */
    public function __construct(Actor $actor, Server $server)
    {
        $this->setServer($server);
        $this->actor = $actor;
    }

    /**
     * Server instance setter
     * 
     * @param \ActivityPhp\Server $server
     */
    public function setServer(Server $server)
    {
        $this->server = $server;
    }

    /**
     * Server instance getter
     * 
     * @return \ActivityPhp\Server
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * Configuration shortcut
     * 
     * @param  string $param
     * @return mixed A configuration parameter or an instance of
     *  \ActivityPhp\Server\Configuration\AbstractConfiguration
     */
    public function config(string $param)
    {
        return $this->getServer()->config($param);
    }

    /**
     * Wrap an object into a Create activity
     * 
     * @see    https://www.w3.org/TR/activitypub/#object-without-create
     *
     * @param  \ActivityPhp\Type\AbstractObject $object
     * @return \ActivityPhp\Type\Core\AbstractActivity
     */
    protected function wrapObject(AbstractObject $object)
    {
        $activity = Type::create('Create', [
            '@context'  => $object->get('@context'),
            'actor'     => $this->actorUrl(),
            'published' => isset($object->published)
                ? $object->published : date('Y-m-dTH:i:sZ'),
        ]);

        if (isset($object->to)) {
            $activity->to = $object->to;
        }

        if (isset($object->cc)) {
            $activity->cc = $object->cc;
        }

        // @see https://www.w3.org/TR/activitypub/#create-activity-outbox
        // Set attributedTo property
        $copy = $object->copy()
            ->set('@context', null)
            ->set('attributedTo', $activity->actor);

        // Create a local id for object
        // @todo A copy of this object should be created
        $copy->id = $this->config('instance.scheme')
            . '://'
            . $this->config('instance.host')
            . preg_replace(
                ['/<handle>/', '/<id>/'],
                [$this->actor->get()->preferredUsername, 'new-id'],
                $this->config('instance.notePath')
        );

        // Create an id for activity
        // @todo An id must be generated for the activity
        $activity->id = $this->config('instance.scheme')
            . '://'
            . $this->config('instance.host')
            . preg_replace(
                ['/<handle>/', '/<id>/'],
                [$this->actor->get()->preferredUsername, 'new-id'],
                $this->config('instance.activityPath')
        );

        // Attach as an object property
        $activity->object = $copy->toArray();

        return $activity;
    }

    /**
     * Get actor id
     * 
     * @todo Handle non local actors
     * @return string
     */
    protected function actorUrl()
    {
        return $this->config('instance.scheme')
            . '://'
            . $this->config('instance.host')
            . preg_replace(
                ['/<handle>/'],
                [$this->actor->get()->preferredUsername],
                $this->config('instance.actorPath')
            );
    }
}
