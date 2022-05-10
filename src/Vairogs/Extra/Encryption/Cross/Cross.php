<?php declare(strict_types = 1);

namespace Vairogs\Extra\Encryption\Cross;

use Vairogs\Utils\Helper\Char;
use function strlen;

final class Cross
{
    public function encrypt(string $string, string $key): string
    {
        if ('' === $string) {
            return '';
        }

        $length = strlen(string: $string);
        $byte = [];
        $result = $string[0];

        for ($i = 0; $i < $length; $i++) {
            $byte[$i] = (new Char())->char2byte(char: $string[$i]);
        }

        for ($i = 1; $i < $length; $i++) {
            $byte[$i] = ($byte[$i] ^ $byte[$i - 1]) + (new Char())->char2byte(char: $key[$i % strlen(string: $key)]);
            $result .= (new Char())->byte2char(byte: $byte[$i]);
        }

        return $result;
    }

    public function decrypt(string $string, string $key): string
    {
        if ('' === $string) {
            return '';
        }

        $length = strlen(string: $string);
        $byte = [];
        $result = '';

        for ($i = 0; $i < $length; $i++) {
            $byte[$i] = (new Char())->char2byte(char: $string[$i]);
        }

        for ($i = $length - 1; $i > 0; $i--) {
            $byte[$i] = ($byte[$i] - (new Char())->char2byte(char: $key[$i % strlen(string: $key)])) ^ $byte[$i - 1];
            $result = (new Char())->byte2char(byte: $byte[$i]) . $result;
        }

        return $string[0] . $result;
    }
}
