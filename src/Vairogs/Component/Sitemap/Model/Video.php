<?php declare(strict_types = 1);

namespace Vairogs\Component\Sitemap\Model;

use DateTime;

class Video
{
    /**
     * @var string
     */
    protected string $thumbnailLoc;

    /**
     * @var string
     */
    protected string $title;

    /**
     * @var string
     */
    protected string $description;

    /**
     * @var null|string
     */
    protected ?string $contentLoc;

    /**
     * @var null|string
     */
    protected ?string $playerLoc;

    /**
     * @var null|int
     */
    protected ?int $duration;

    /**
     * @var null|DateTime
     */
    protected ?DateTime $expirationDate;

    /**
     * @var null|float
     */
    protected ?float $rating;

    /**
     * @var null|int
     */
    protected ?int $viewCount;

    /**
     * @var null|DateTime
     */
    protected ?DateTime $publicationDate;

    /**
     * @var null|string
     */
    protected ?string $familyFriendly;

    /**
     * @var string[]
     */
    protected array $tags = [];

    /**
     * @var null|string
     */
    protected ?string $category;

    /**
     * @var array
     */
    protected array $restrictions = [];

    /**
     * @var null|string
     */
    protected ?string $galleryLoc;

    /**
     * @var null|string
     */
    protected ?string $requiresSubscription;

    /**
     * @var null|string
     */
    protected ?string $uploader;

    /**
     * @var array
     */
    protected array $platforms = [];

    /**
     * @var null|string
     */
    protected ?string $live;
}
