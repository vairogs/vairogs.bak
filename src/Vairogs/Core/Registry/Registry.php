<?php declare(strict_types = 1);

namespace Vairogs\Core\Registry;

use InvalidArgumentException;
use IteratorAggregate;

use function iterator_to_array;
use function sprintf;

class Registry
{
    /**
     * @var HasRegistry[]
     */
    private array $clients;

    public function __construct(IteratorAggregate $clients)
    {
        /* @noinspection PhpRedundantOptionalArgumentInspection */
        $this->clients = iterator_to_array(iterator: $clients, preserve_keys: true);
    }

    public function getClient(string $name): HasRegistry
    {
        foreach ($this->clients as $client) {
            if ($name === $client->getName()) {
                return $client;
            }
        }

        throw new InvalidArgumentException(message: sprintf('Client "%s" does not exist', $name));
    }

    /**
     * @return array<int, HasRegistry>
     */
    public function getClients(): array
    {
        return $this->clients;
    }
}
