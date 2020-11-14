<?php declare(strict_types = 1);

namespace Vairogs\Component\Sitemap\Model;

use DateTime;

class Video
{
    protected string $thumbnailLoc;

    protected string $title;

    protected string $description;

    protected ?string $contentLoc;

    protected ?string $playerLoc;

    protected ?int $duration;

    protected ?DateTime $expirationDate;

    protected ?float $rating;

    protected ?int $viewCount;

    protected ?DateTime $publicationDate;

    protected ?string $familyFriendly;

    /**
     * @var string[]
     */
    protected array $tags = [];

    protected ?string $category;

    protected array $restrictions = [];

    protected ?string $galleryLoc;

    protected ?string $requiresSubscription;

    protected ?string $uploader;

    protected array $platforms = [];

    protected ?string $live;
}
