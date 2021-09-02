<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Doctrine\Model\Factory;

use InvalidArgumentException;
use Vairogs\Component\Utils\Doctrine\Model\Log;
use function class_exists;
use function sprintf;

class LogArrayFactory
{
    public static function create(array $data, string $class = Log::class): Log
    {
        if (!class_exists($class)) {
            throw new InvalidArgumentException(sprintf('Class % does not exist', $class));
        }

        $object = new $class();

        if (!$object instanceof Log) {
            throw new InvalidArgumentException(sprintf('Class %s must extend %s', $class, Log::class));
        }

        return $object->setMessage($data['message'] ?? '')
            ->setLevel($data['level'] ?? 0)
            ->setLevelName($data['level_name'] ?? '')
            ->setExtra($data['extra'] ?? [])
            ->setContext($data['context'] ?? []);
    }
}
