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

    public function getThumbnailLoc(): string
    {
        return $this->thumbnailLoc;
    }

    public function setThumbnailLoc(string $thumbnailLoc): static
    {
        $this->thumbnailLoc = $thumbnailLoc;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getContentLoc(): ?string
    {
        return $this->contentLoc;
    }

    public function setContentLoc(?string $contentLoc): static
    {
        $this->contentLoc = $contentLoc;

        return $this;
    }

    public function getPlayerLoc(): ?string
    {
        return $this->playerLoc;
    }

    public function setPlayerLoc(?string $playerLoc): static
    {
        $this->playerLoc = $playerLoc;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getExpirationDate(): ?DateTime
    {
        return $this->expirationDate;
    }

    public function setExpirationDate(?DateTime $expirationDate): static
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(?float $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    public function getViewCount(): ?int
    {
        return $this->viewCount;
    }

    public function setViewCount(?int $viewCount): static
    {
        $this->viewCount = $viewCount;

        return $this;
    }

    public function getPublicationDate(): ?DateTime
    {
        return $this->publicationDate;
    }

    public function setPublicationDate(?DateTime $publicationDate): static
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }

    public function getFamilyFriendly(): ?string
    {
        return $this->familyFriendly;
    }

    public function setFamilyFriendly(?string $familyFriendly): static
    {
        $this->familyFriendly = $familyFriendly;

        return $this;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function setTags(array $tags): static
    {
        $this->tags = $tags;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getRestrictions(): array
    {
        return $this->restrictions;
    }

    public function setRestrictions(array $restrictions): static
    {
        $this->restrictions = $restrictions;

        return $this;
    }

    public function getGalleryLoc(): ?string
    {
        return $this->galleryLoc;
    }

    public function setGalleryLoc(?string $galleryLoc): static
    {
        $this->galleryLoc = $galleryLoc;

        return $this;
    }

    public function getRequiresSubscription(): ?string
    {
        return $this->requiresSubscription;
    }

    public function setRequiresSubscription(?string $requiresSubscription): static
    {
        $this->requiresSubscription = $requiresSubscription;

        return $this;
    }

    public function getUploader(): ?string
    {
        return $this->uploader;
    }

    public function setUploader(?string $uploader): static
    {
        $this->uploader = $uploader;

        return $this;
    }

    public function getPlatforms(): array
    {
        return $this->platforms;
    }

    public function setPlatforms(array $platforms): static
    {
        $this->platforms = $platforms;

        return $this;
    }

    public function getLive(): ?string
    {
        return $this->live;
    }

    public function setLive(?string $live): static
    {
        $this->live = $live;

        return $this;
    }
}
