<?php declare(strict_types = 1);

namespace Vairogs\Component\Sitemap\Model\Traits;

use DateTimeInterface;

trait VideoSetters
{
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
