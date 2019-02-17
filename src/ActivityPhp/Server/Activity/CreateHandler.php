<?php

/*
 * This file is part of the ActivityPhp package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPhp\Server\Activity;

use ActivityPhp\Type\Core\AbstractActivity;

/**
 * A Create activity handler
 */ 
class CreateHandler extends AbstractHandler
{
    /**
     * @var \ActivityPhp\Type\Core\AbstractActivity
     */
    private $activity;

    /**
     * Constructor
     * 
     * @param \ActivityPhp\Type\Core\AbstractActivity $activity
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
            $this->activity->get('id')
        );
        
        return $this;
    }
}
