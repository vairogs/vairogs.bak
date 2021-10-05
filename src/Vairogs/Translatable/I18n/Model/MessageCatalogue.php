<?php declare(strict_types = 1);

namespace Vairogs\Translatable\I18n\Model;

use InvalidArgumentException;

class MessageCatalogue
{
    private string $locale;
    private array $domains = [];

    public function setLocale(string $locale): static
    {
        $this->locale = $locale;

        return $this;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function add(Message $message): static
    {
        $this
            ->getOrCreateDomain($message->getDomain())
            ->add($message);

        return $this;
    }

    public function set(Message $message, $force = false): static
    {
        $this
            ->getOrCreateDomain($message->getDomain())
            ->set($message, $force);

        return $this;
    }

    public function get($id, $domain = 'messages')
    {
        return $this->getDomain($domain)->get($id);
    }

    public function has(Message $message): bool
    {
        if (!$this->hasDomain($message->getDomain())) {
            return false;
        }

        return $this->getDomain($message->getDomain())->has($message->getId());
    }

    public function merge(self $catalogue): static
    {
        foreach ($catalogue->getDomains() as $name => $domainCatalogue) {
            $this->getOrCreateDomain($name)->merge($domainCatalogue);
        }

        return $this;
    }

    public function hasDomain($domain): bool
    {
        return isset($this->domains[$domain]);
    }

    public function getDomain($domain)
    {
        if (!$this->hasDomain($domain)) {
            throw new InvalidArgumentException(sprintf('There is no domain with name "%s".', $domain));
        }

        return $this->domains[$domain];
    }

    public function getDomains(): array
    {
        return $this->domains;
    }

    private function getOrCreateDomain($domain)
    {
        if (!$this->hasDomain($domain)) {
            $this->domains[$domain] = new MessageCollection();
        }

        return $this->domains[$domain];
    }
}
