<?php declare(strict_types = 1);

namespace Vairogs\Extra\Constants\Enum;

enum Group: string
{
    case READ = 'read';
    case WRITE = 'write';
    case DELETE = 'delete';
}
