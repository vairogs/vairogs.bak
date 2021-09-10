<?php declare(strict_types = 1);

namespace Vairogs\Component\Sitemap\Model\Traits;

use DateTimeInterface;

trait VideoGetters
{
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
}
