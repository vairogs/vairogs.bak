<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Doctrine;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use const null;

trait CreatedModified
{
    /**
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     * @var DateTime|null
     */
    protected ?DateTime $createdAt;

    /**
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     * @var DateTime|null
     */
    protected ?DateTime $modifiedAt;

    /**
     * @return DateTime
     */
    public function getModifiedAt(): DateTime
    {
        return $this->modifiedAt;
    }

    /**
     * @param DateTime $modifiedAt
     */
    public function setModifiedAt(DateTime $modifiedAt): void
    {
        $this->modifiedAt = $modifiedAt;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * @throws Exception
     */
    public function updatedTimestamps(): void
    {
        $this->setModifiedAt(new DateTime());

        if (null === $this->createdAt) {
            $this->setCreatedAt(new DateTime());
        }
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
