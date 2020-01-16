<?php

namespace ActivityPhp\Server\Activity;

use Psr\Http\Message\ResponseFactoryInterface;

/**
 * Abstract class for all activity handlers
 */ 
abstract class AbstractHandler implements HandlerInterface
{
    /**
     * @var ResponseFactoryInterface
     */
    protected $responseFactory;

    /**
     * @param ResponseFactoryInterface $responseFactory
     */
    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }
}
