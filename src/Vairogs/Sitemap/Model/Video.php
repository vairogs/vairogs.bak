<?php declare(strict_types = 1);

namespace Vairogs\Sitemap\Model;

use DateTimeInterface;
use Vairogs\Sitemap\Model\Traits\VideoGetters;
use Vairogs\Sitemap\Model\Traits\VideoSetters;

class Video
{
    use VideoGetters;
    use VideoSetters;
    protected ?DateTimeInterface $expirationDate = null;
    protected ?DateTimeInterface $publicationDate = null;
    protected ?float $rating = null;
    protected ?int $duration = null;
    protected ?int $viewCount = null;
    protected ?string $category = null;
    protected ?string $contentLoc = null;
    protected ?string $familyFriendly = null;
    protected ?string $galleryLoc = null;
    protected ?string $live = null;
    protected ?string $playerLoc = null;
    protected ?string $requiresSubscription = null;
    protected ?string $uploader = null;
    protected array $platforms = [];
    protected array $restrictions = [];
    protected array $tags = [];
    protected string $description;
    protected string $thumbnailLoc;
    protected string $title;
}
