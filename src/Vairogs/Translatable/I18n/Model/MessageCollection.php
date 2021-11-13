<?php declare(strict_types = 1);

namespace Vairogs\Translatable\I18n\Model;

use InvalidArgumentException;
use RuntimeException;

class MessageCollection
{
    private MessageCatalogue $catalogue;
    private array $messages = [];

    public function setCatalogue(MessageCatalogue $catalogue): static
    {
        $this->catalogue = $catalogue;

        return $this;
    }

    public function getCatalogue(): MessageCatalogue
    {
        return $this->catalogue;
    }

    public function add(Message $message): void
    {
        if (isset($this->messages[$id = $message->getId()])) {
            $this->checkConsistency($this->messages[$id], $message);
            $this->messages[$id]->merge($message);

            return;
        }

        $this->messages[$id] = $message;
    }

    public function set(Message $message, $force = false): void
    {
        $id = $message->getId();
        if (!$force && isset($this->messages[$id])) {
            $this->checkConsistency($this->messages[$id], $message);
        }

        $this->messages[$id] = $message;
    }

    public function get($id)
    {
        if (!isset($this->messages[$id])) {
            throw new InvalidArgumentException(sprintf('There is no message with id "%s".', $id));
        }

        return $this->messages[$id];
    }

    public function has($id): bool
    {
        return isset($this->messages[$id]);
    }

    public function sort($callback): void
    {
        if (!is_callable($callback)) {
            throw new InvalidArgumentException('$callback must be a valid callback.');
        }

        uasort($this->messages, $callback);
    }

    public function filter($callback): static
    {
        if (!is_callable($callback)) {
            throw new InvalidArgumentException('$callback must be a valid callback.');
        }

        $this->messages = array_filter($this->messages, $callback);

        return $this;
    }

    public function replace(array $messages): static
    {
        $this->messages = $messages;

        return $this;
    }

    public function all(): array
    {
        return $this->messages;
    }

    public function merge(self $domain): static
    {
        foreach ($domain->all() as $message) {
            $this->add($message);
        }

        return $this;
    }

    private function checkConsistency(Message $oldMessage, Message $newMessage): void
    {
        $oldDesc = $oldMessage->getDesc();
        $newDesc = $newMessage->getDesc();

        if ($oldDesc !== $newDesc && '' !== $oldDesc && '' !== $newDesc) {
            throw new RuntimeException(sprintf("The message '%s' exists with two different descs: '%s' in %s, and '%s' in %s", $oldMessage->getId(), $oldMessage->getDesc(), current($oldMessage->getSources()), $newMessage->getDesc(), current($newMessage->getSources())));
        }
    }
}
