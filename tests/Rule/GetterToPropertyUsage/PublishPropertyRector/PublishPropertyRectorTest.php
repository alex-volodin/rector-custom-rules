<?php

declare(strict_types=1);

namespace RectorCustomRules\Tests\Rule\GetterToPropertyUsage\PublishPropertyRector;

use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class PublishPropertyRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public function provideData(): \Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/config.php';
    }
}
