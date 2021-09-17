<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\HttpFoundation;

use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;
use function flush;
use function sprintf;
use function strlen;

class StreamResponse extends Response
{
    public const BUFFER_SIZE = 4096;

    public function __construct(ResponseInterface $response, private int $bufferSize = self::BUFFER_SIZE)
    {
        parent::__construct(content: null, status: $response->getStatusCode(), headers: $response->getHeaders());

        $this->content = $response->getBody();
    }

    public function getContent(): bool|string
    {
        return false;
    }

    public function sendContent(): void
    {
        $chunked = $this->headers->has(key: 'Transfer-Encoding');
        $this->content->seek(offset: 0);
        while (true) {
            $chunk = $this->content->read(length: $this->bufferSize);

            if ($chunked) {
                echo sprintf("%x\r\n", strlen($chunk));
            }

            echo $chunk;

            if ($chunked) {
                echo "\r\n";
            }

            flush();

            if (!$chunk) {
                return;
            }
        }
    }
}
