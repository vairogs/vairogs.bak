<?php declare(strict_types = 1);

namespace Vairogs\Auth\OpenIDConnect\Utils;

use InvalidArgumentException;
use Traversable;
use Vairogs\Auth\OpenIDConnect\OpenIDConnectProvider;
use function iterator_to_array;
use function sprintf;

class OpenIDConnectRegistry
{
    /**
     * @var OpenIDConnectProvider[]
     */
    private array $clients;

    public function __construct(Traversable $clients)
    {
        $this->clients = iterator_to_array(iterator: $clients, preserve_keys: true);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getClient(string $name): OpenIDConnectProvider
    {
        foreach ($this->clients as $client) {
            if ($name === $client->getName()) {
                return $client;
            }
        }

        throw new InvalidArgumentException(message: sprintf('Client "%s" does not exist', $name));
    }

    public function getClients(): iterable
    {
        return $this->clients;
    }
}
