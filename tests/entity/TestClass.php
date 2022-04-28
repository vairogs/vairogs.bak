<?php declare(strict_types = 1);

namespace Vairogs\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Vairogs\Tests\Repository\TestClassRepository;
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
