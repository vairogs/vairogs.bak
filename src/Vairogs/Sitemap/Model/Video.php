<?php declare(strict_types = 1);

namespace Vairogs\Sitemap\Model;

use DateTimeInterface;

class Video
{
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

    public function getThumbnailLoc(): string
    {
        return $this->thumbnailLoc;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getContentLoc(): ?string
    {
        return $this->contentLoc;
    }

    public function getPlayerLoc(): ?string
    {
        return $this->playerLoc;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function getExpirationDate(): ?DateTimeInterface
    {
        return $this->expirationDate;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function getViewCount(): ?int
    {
        return $this->viewCount;
    }

    public function getPublicationDate(): ?DateTimeInterface
    {
        return $this->publicationDate;
    }

    public function getFamilyFriendly(): ?string
    {
        return $this->familyFriendly;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function getRestrictions(): array
    {
        return $this->restrictions;
    }

    public function getGalleryLoc(): ?string
    {
        return $this->galleryLoc;
    }

    public function getRequiresSubscription(): ?string
    {
        return $this->requiresSubscription;
    }

    public function getUploader(): ?string
    {
        return $this->uploader;
    }

    public function getPlatforms(): array
    {
        return $this->platforms;
    }

    public function getLive(): ?string
    {
        return $this->live;
    }

    public function setThumbnailLoc(string $thumbnailLoc): static
    {
        $this->thumbnailLoc = $thumbnailLoc;

        return $this;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function setContentLoc(?string $contentLoc): static
    {
        $this->contentLoc = $contentLoc;

        return $this;
    }

    public function setPlayerLoc(?string $playerLoc): static
    {
        $this->playerLoc = $playerLoc;

        return $this;
    }

    public function setDuration(?int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function setExpirationDate(?DateTimeInterface $expirationDate): static
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }

    public function setRating(?float $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    public function setViewCount(?int $viewCount): static
    {
        $this->viewCount = $viewCount;

        return $this;
    }

    public function setPublicationDate(?DateTimeInterface $publicationDate): static
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }

    public function setFamilyFriendly(?string $familyFriendly): static
    {
        $this->familyFriendly = $familyFriendly;

        return $this;
    }

    public function setTags(array $tags): static
    {
        $this->tags = $tags;

        return $this;
    }

    public function setCategory(?string $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function setRestrictions(array $restrictions): static
    {
        $this->restrictions = $restrictions;

        return $this;
    }

    public function setGalleryLoc(?string $galleryLoc): static
    {
        $this->galleryLoc = $galleryLoc;

        return $this;
    }

    public function setRequiresSubscription(?string $requiresSubscription): static
    {
        $this->requiresSubscription = $requiresSubscription;

        return $this;
    }

    public function setUploader(?string $uploader): static
    {
        $this->uploader = $uploader;

        return $this;
    }

    public function setPlatforms(array $platforms): static
    {
        $this->platforms = $platforms;

        return $this;
    }

    public function setLive(?string $live): static
    {
        $this->live = $live;

        return $this;
    }
}
