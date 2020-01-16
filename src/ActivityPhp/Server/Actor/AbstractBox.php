<?php

namespace ActivityPhp\Server\Actor;

use ActivityPhp\Server;
use ActivityPhp\Server\Actor;
use ActivityPhp\Type;
use ActivityPhp\Type\AbstractObject;

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
     * @var Server
     */
    protected $server;

    /**
     * @var Actor
     */
    protected $actor;

    /**
     * A box definition
     *
     * @var Type\Core\OrderedCollection
     */
    protected $orderedCollection;

    /**
     * Box constructor
     * 
     * @param Actor $actor
     * @param Server $server
     */
    public function __construct(Actor $actor, Server $server)
    {
        $this->server = $server;
        $this->actor = $actor;
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
        return $this->server->config($param);
    }

    /**
     * Wrap an object into a Create activity
     *
     * @see    https://www.w3.org/TR/activitypub/#object-without-create
     *
     * @param AbstractObject $object
     * @return Type\Core\AbstractActivity
     * @throws \Exception
     */
    protected function wrapObject(AbstractObject $object)
    {
        /** @var Type\Core\AbstractActivity $activity */
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
