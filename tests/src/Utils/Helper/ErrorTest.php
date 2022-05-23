<?php declare(strict_types = 1);

namespace Vairogs\Tests\Utils\Helper;

use Exception;
use Vairogs\Tests\Assets\VairogsTestCase;
use Vairogs\Utils\Helper\Error;

class ErrorTest extends VairogsTestCase
{
    public function testGetExceptionTraceAsString(): void
    {
        $this->assertIsString(actual: (new Error())->getExceptionTraceAsString(exception: new Exception(previous: new Exception())));
    }
}
