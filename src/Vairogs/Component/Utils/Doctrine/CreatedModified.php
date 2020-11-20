<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Doctrine;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Exception;

trait CreatedModified
{
    /**
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    protected ?DateTime $creationDate = null;

    /**
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    protected ?DateTime $modificationDate;

    /**
     * @return DateTime
     */
    public function getModificationDate(): DateTime
    {
        return $this->modificationDate;
    }

    /**
     * @param DateTime $modificationDate
     */
    public function setModificationDate(DateTime $modificationDate): void
    {
        $this->modificationDate = $modificationDate;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * @throws Exception
     */
    public function updatedTimestamps(): void
    {
        $this->setModificationDate(new DateTime());

        if (null === $this->creationDate) {
            $this->setCreationDate(new DateTime());
        }
    }

    /**
     * @return DateTime
     */
    public function getCreationDate(): DateTime
    {
        return $this->creationDate;
    }

    /**
     * @param DateTime $creationDate
     */
    public function setCreationDate(DateTime $creationDate): void
    {
        $this->creationDate = $creationDate;
    }
}
