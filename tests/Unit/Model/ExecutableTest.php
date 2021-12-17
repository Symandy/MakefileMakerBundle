<?php

declare(strict_types=1);

namespace Symandy\Tests\MakefileMakerBundle\Unit\Model;

use PHPUnit\Framework\TestCase;
use Symandy\MakefileMakerBundle\Model\Executable;

final class ExecutableTest extends TestCase
{

    public function test_set_properties(): void
    {
        $executable = new Executable('name-test', 'test', 'console', '/path/to/test/dir');

        self::assertSame('name-test', $executable->getName());
        self::assertSame('test', $executable->getFilename());
        self::assertSame('console', $executable->getOutput());
        self::assertSame('/path/to/test/dir', $executable->getPath());
    }

}
