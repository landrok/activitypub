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
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * A Create activity handler
 */ 
class CreateHandler extends AbstractHandler
{
    /**
     * @var AbstractActivity
     */
    private $activity;

    /**
     * Constructor
     *
     * @param AbstractActivity $activity
     * @param ResponseFactoryInterface $responseFactory
     */
    public function __construct(AbstractActivity $activity, ResponseFactoryInterface $responseFactory)
    {
        parent::__construct($responseFactory);

        $this->activity = $activity;   
    }

    /**
     * Handle activity
     * 
     * @return $this
     */
    public function handle(): ResponseInterface
    {
        return $this->responseFactory->createResponse(201)
            ->withHeader('location', $this->activity->get('id'));
    }
}
