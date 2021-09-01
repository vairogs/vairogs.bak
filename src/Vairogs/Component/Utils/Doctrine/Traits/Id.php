<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Doctrine\Traits;

use Doctrine\ORM\Mapping as ORM;
use Vairogs\Extra\Constants\Type;

trait Id
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: Type::INTEGER, unique: true)]
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type=Type::INTEGER, unique=true)
     */
    protected ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }
}
