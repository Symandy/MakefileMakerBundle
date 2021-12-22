<?php

declare(strict_types=1);

namespace Symandy\Tests\MakefileMakerBundle\Unit\DependencyInjection\Compiler;

use Exception;
use PHPUnit\Framework\TestCase;
use Symandy\MakefileMakerBundle\DependencyInjection\Compiler\AddSymfonyConsoleExecutablePass;
use Symandy\MakefileMakerBundle\Registry\ExecutableRegistry;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Process\ExecutableFinder;

final class AddSymfonyConsoleExecutablePassTest extends TestCase
{

    /**
     * @throws Exception
     */
    public function testProcessAddSymfonyExecutables(): void
    {
        $container = new ContainerBuilder();
        $container
            ->register('symandy_makefile_maker.registry.executable_registry', ExecutableRegistry::class)
            ->setPublic(true)
        ;

        $container->addCompilerPass(new AddSymfonyConsoleExecutablePass());
        $container->compile();

        /** @var ExecutableRegistry $registry */
        $registry = $container->get('symandy_makefile_maker.registry.executable_registry');

        $finder = new ExecutableFinder();

        if (null !== $finder->find('symfony')) {
            self::assertCount(2, $registry->all());

            return;
        } else if (null !== $finder->find('php')) {
            self::assertCount(1, $registry->all());

            return;
        }

        self::assertCount(0, $registry->all());

        self::markTestIncomplete('Test cannot assert symfony_bin and symfony_console have been added to registry');
    }

}
