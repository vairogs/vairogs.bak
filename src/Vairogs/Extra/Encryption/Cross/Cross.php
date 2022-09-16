<?php declare(strict_types = 1);

namespace Vairogs\Extra\Encryption\Cross;

use Vairogs\Utils\Helper\Convert;

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
            $byte[$i] = (new Convert())->char2byte(char: $string[$i]);
        }

        for ($i = 1; $i < $length; $i++) {
            $byte[$i] = ($byte[$i] ^ $byte[$i - 1]) + (new Convert())->char2byte(char: $key[$i % strlen(string: $key)]);
            $result .= (new Convert())->byte2char(byte: $byte[$i]);
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
            $byte[$i] = (new Convert())->char2byte(char: $string[$i]);
        }

        for ($i = $length - 1; $i > 0; $i--) {
            $byte[$i] = ($byte[$i] - (new Convert())->char2byte(char: $key[$i % strlen(string: $key)])) ^ $byte[$i - 1];
            $result = (new Convert())->byte2char(byte: $byte[$i]) . $result;
        }

        return $string[0] . $result;
    }
}
