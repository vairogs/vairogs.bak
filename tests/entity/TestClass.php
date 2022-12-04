<?php declare(strict_types = 1);

namespace Vairogs\Tests\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Vairogs\Tests\Assets\Utils\Repository\TestClassRepository;
use Vairogs\Utils\Doctrine\Traits\Entity;

#[ORM\Entity(repositoryClass: TestClassRepository::class)]
#[ORM\HasLifecycleCallbacks]
class TestClass
{
    use Entity;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $message = null;

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }
}
