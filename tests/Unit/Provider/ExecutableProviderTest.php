<?php

declare(strict_types=1);

namespace Symandy\Tests\MakefileMakerBundle\Unit\Provider;

use PHPUnit\Framework\TestCase;
use Symandy\MakefileMakerBundle\Provider\ExecutableProvider;
use Symandy\MakefileMakerBundle\Registry\ExecutableRegistry;
use Symandy\MakefileMakerBundle\Registry\ExecutableRegistryInterface;
use Symandy\Tests\MakefileMakerBundle\DataFixtures\GroupFixtureGenerator;

final class ExecutableProviderTest extends TestCase
{

    public function testProvideUsedExecutables(): void
    {
        $executableRegistry = $this->generateExecutableRegistry();

        $expectedExecutables = [
            $executableRegistry->get('test-executable-1'),
            $executableRegistry->get('test-executable-2')
        ];

        $groups = GroupFixtureGenerator::generateGroups($executableRegistry);

        $executableProvider = new ExecutableProvider();
        $usedExecutables = $executableProvider->provideUsedExecutables($groups);

        self::assertEquals($expectedExecutables, $usedExecutables);
    }

    private function generateExecutableRegistry(): ExecutableRegistryInterface
    {
        $executableRegistry = new ExecutableRegistry();
        $executableRegistry->add(
            'test-executable-1',
            'test1',
            'console',
            '/path/to/test/dir'
        );

        $executableRegistry->add(
            'test-executable-2',
            'test2',
            null,
            '/path/to/test/dir'
        );

        $executableRegistry->add(
            'test-executable-3-unused',
            'test3',
            null,
            '/path/to/test/dir'
        );

        return $executableRegistry;
    }

}
