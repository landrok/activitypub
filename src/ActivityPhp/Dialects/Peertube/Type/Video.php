<?php

declare(strict_types=1);

namespace ActivityPhp\Dialects\Peertube\Type;

use ActivityPhp\Type\Extended\Object\Video as BaseVideo;

final class Video extends BaseVideo
{
    /**
     * @var string
     */
    protected $uuid;

    /**
     * @var string
     */
    protected $category;

    /**
     * @var string
     */
    protected $language;

    /**
     * @var int
     */
    protected $views;

    /**
     * @var int
     */
    protected $sensitive;

    /**
     * @var int
     */
    protected $waitTranscoding;

    /**
     * @var string
     */
    protected $state;

    /**
     * @var int
     */
    protected $commentsEnabled;

    /**
     * @var string
     */
    protected $support;

    /**
     * @var string
     */
    protected $subtitleLanguage;

    /**
     * @var int
     */
    protected $likes;

    /**
     * @var int
     */
    protected $dislikes;

    /**
     * @var int
     */
    protected $shares;

    /**
     * @var string
     */
    protected $comments;

    /**
     * @var string
     */
    protected $licence;

    /**
     * @var int
     */
    protected $downloadEnabled;

    /**
     * @var string
     */
    protected $originallyPublishedAt;
}