<?php declare(strict_types = 1);

namespace Vairogs\Tests\Utils\Locator;

use PhpParser\Node\Stmt\Class_;
use Vairogs\Assets\Controller\TestController;
use Vairogs\Assets\TestKernel;
use Vairogs\Assets\VairogsTestCase;
use Vairogs\Utils\Locator\Finder;
use function getcwd;

class FinderTest extends VairogsTestCase
{
    public function testFinder(): void
    {
        $spls = (new Finder(directories: [getcwd() . '/tests/assets/'], types: [Class_::class], namespace: 'Vairogs\Assets\Utils'))->locate()->getClassMap();
        $this->assertArrayHasKey(key: TestController::class, array: $spls);
        $spls = (new Finder(directories: [getcwd() . '/tests/assets/'], namespace: 'Vairogs\Assets\Utils'))->locate();
        $this->assertArrayHasKey(key: TestKernel::class, array: $spls->getClassMap());
        $this->assertNull(actual: $spls->getClass(class: TestKernel::class)->getTest());
    }
}
