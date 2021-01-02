<?php declare(strict_types = 1);

namespace Vairogs\Component\Sitemap\Model;

use DateTime;

class Video
{
    protected string $thumbnailLoc;
    protected string $title;
    protected string $description;
    protected ?string $contentLoc = null;
    protected ?string $playerLoc = null;
    protected ?int $duration = null;
    protected ?DateTime $expirationDate = null;
    protected ?float $rating = null;
    protected ?int $viewCount = null;
    protected ?DateTime $publicationDate = null;
    protected ?string $familyFriendly = null;
    protected array $tags = [];
    protected ?string $category = null;
    protected array $restrictions = [];
    protected ?string $galleryLoc = null;
    protected ?string $requiresSubscription = null;
    protected ?string $uploader = null;
    protected array $platforms = [];
    protected ?string $live = null;
}
