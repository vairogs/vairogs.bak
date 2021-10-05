<?php declare(strict_types = 1);

namespace Vairogs\Translatable\I18n\Model;

use JetBrains\PhpStorm\Pure;
use RuntimeException;
use Stringable;

class Message implements Stringable
{
    private string $id;
    private bool $new = true;
    private string $domain;
    private ?string $localeString;
    private ?string $meaning;
    private ?string $desc;
    private array $sources = [];

    public function __construct(string $id, $domain = 'messages')
    {
        $this->id = $id;
        $this->domain = $domain;
    }

    public function __toString(): string
    {
        return $this->id;
    }

    public static function forThisFile($id, $domain = 'messages'): static
    {
        $message = new static($id, $domain);

        $trace = debug_backtrace(0);
        if (isset($trace[0]['file'])) {
            $message->addSource(new FileSource($trace[0]['file'], $trace[0]['line']));
        }

        return $message;
    }

    #[Pure]
 public static function create($id, $domain = 'messages'): static
 {
     return new static($id, $domain);
 }

    public function addSource(SourceInterface $source): static
    {
        if ($this->hasSource($source)) {
            return $this;
        }

        $this->sources[] = $source;

        return $this;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function isNew(): bool
    {
        return $this->new;
    }

    public function getLocaleString(): string
    {
        return $this->localeString ?? ($this->new ? ($this->desc ?? $this->id) : '');
    }

    public function getSourceString(): string
    {
        return $this->desc ?: $this->id;
    }

    public function getMeaning(): ?string
    {
        return $this->meaning;
    }

    public function getDesc(): ?string
    {
        return $this->desc;
    }

    public function getSources(): array
    {
        return $this->sources;
    }

    public function setMeaning(?string $meaning): static
    {
        $this->meaning = $meaning;

        return $this;
    }

    public function setNew(bool $bool): static
    {
        $this->new = $bool;

        return $this;
    }

    public function setDesc(?string $desc): static
    {
        $this->desc = $desc;

        return $this;
    }

    public function setLocaleString(string $str): static
    {
        $this->localeString = $str;

        return $this;
    }

    public function setSources(array $sources = []): static
    {
        $this->sources = $sources;

        return $this;
    }

    public function hasLocaleString(): bool
    {
        return !empty($this->localeString);
    }

    public function merge(self $message): void
    {
        if ($this->id !== $message->getId()) {
            throw new RuntimeException(sprintf('You can only merge messages with the same id. Expected id "%s", but got "%s".', $this->id, $message->getId()));
        }

        if (null !== $meaning = $message->getMeaning()) {
            $this->meaning = $meaning;
        }

        if (null !== $desc = $message->getDesc()) {
            $this->desc = $desc;
            $this->localeString = null;
            if ($message->hasLocaleString()) {
                $this->localeString = $message->getLocaleString();
            }
        }

        foreach ($message->getSources() as $source) {
            $this->addSource($source);
        }

        $this->setNew($message->isNew());
    }

    public function mergeExisting(self $message): void
    {
        if ($this->id !== $message->getId()) {
            throw new RuntimeException(sprintf('You can only merge messages with the same id. Expected id "%s", but got "%s".', $this->id, $message->getId()));
        }

        if (null !== $meaning = $message->getMeaning()) {
            $this->meaning = $meaning;
        }

        if (null !== $desc = $message->getDesc()) {
            $this->desc = $desc;
        }

        $this->setNew($message->isNew());
        if ($localeString = $message->getLocaleString()) {
            $this->localeString = $localeString;
        }
    }

    public function mergeScanned(self $message): void
    {
        if ($this->id !== $message->getId()) {
            throw new RuntimeException(sprintf('You can only merge messages with the same id. Expected id "%s", but got "%s".', $this->id, $message->getId()));
        }

        if (null === $this->getMeaning()) {
            $this->meaning = $message->getMeaning();
        }

        if (null === $this->getDesc()) {
            $this->desc = $message->getDesc();
        }

        $this->sources = [];
        foreach ($message->getSources() as $source) {
            $this->addSource($source);
        }

        if (!$this->getLocaleString()) {
            $this->localeString = $message->getLocaleString();
        }
    }

    public function hasSource(SourceInterface $source): bool
    {
        foreach ($this->sources as $cSource) {
            if ($cSource->equals($source)) {
                return true;
            }
        }

        return false;
    }
}
