<?php

/*
 * This file is part of the ActivityPub package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPub\Server\Activity;

use ActivityPub\Type\Core\AbstractActivity;

/**
 * A Create activity handler
 */ 
class CreateHandler extends AbstractHandler
{
    /**
     * @var \ActivityPub\Type\Core\AbstractActivity
     */
    private $activity;

    /**
     * Constructor
     * 
     * @param \ActivityPub\Type\Core\AbstractActivity $activity
     */
    public function __construct(AbstractActivity $activity)
    {
        parent::__construct();

        $this->activity = $activity;   
    }

    /**
     * Handle activity
     * 
     * @return $this
     */
    public function handle()
    {
        $this->getResponse()->setStatusCode(201);
        $this->getResponse()->headers->set(
            'location',
            $this->activity->id
        );
        
        return $this;
    }
}
