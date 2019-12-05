<?php declare(strict_types = 1);

namespace Vairogs\Component\Sitemap\Model;

use DateTime;

class Video
{
    /**
     * @var string
     */
    protected $thumbnailLoc;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var null|string
     */
    protected $contentLoc;

    /**
     * @var null|string
     */
    protected $playerLoc;

    /**
     * @var null|int
     */
    protected $duration;

    /**
     * @var null|DateTime
     */
    protected $expirationDate;

    /**
     * @var null|float
     */
    protected $rating;

    /**
     * @var null|int
     */
    protected $viewCount;

    /**
     * @var null|DateTime
     */
    protected $publicationDate;

    /**
     * @var null|string
     */
    protected $familyFriendly;

    /**
     * @var string[]
     */
    protected $tags = [];

    /**
     * @var null|string
     */
    protected $category;

    /**
     * @var array
     */
    protected $restrictions = [];

    /**
     * @var null|string
     */
    protected $galleryLoc;

    /**
     * @var null|string
     */
    protected $requiresSubscription;

    /**
     * @var null|string
     */
    protected $uploader;

    /**
     * @var array
     */
    protected $platforms = [];

    /**
     * @var null|string
     */
    protected $live;
}
