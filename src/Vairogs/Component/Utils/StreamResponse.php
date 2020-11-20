<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils;

use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;
use function flush;
use function sprintf;
use function strlen;

class StreamResponse extends Response
{
    public const BUFFER_SIZE = 4096;

    private int $bufferSize;

    /**
     * @param ResponseInterface $response
     * @param int $bufferSize
     */
    public function __construct(ResponseInterface $response, int $bufferSize = self::BUFFER_SIZE)
    {
        parent::__construct(null, $response->getStatusCode(), $response->getHeaders());

        $this->content = $response->getBody();
        $this->bufferSize = $bufferSize;
    }

    /**
     * @return bool|string
     */
    public function getContent()
    {
        return false;
    }

    /**
     * @return Response|void
     */
    public function sendContent()
    {
        $chunked = $this->headers->has('Transfer-Encoding');
        $this->content->seek(0);
        while (true) {
            $chunk = $this->content->read($this->bufferSize);

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
