<?php

declare(strict_types=1);

namespace Symandy\MakefileMakerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Process\ExecutableFinder;

final class AddSymfonyConsoleExecutablePass implements CompilerPassInterface
{

    public function process(ContainerBuilder $container): void
    {
        if (!$container->has('symandy_makefile_maker.registry.executable_registry')) {
            return;
        }

        $definition = $container->getDefinition('symandy_makefile_maker.registry.executable_registry');

        $finder = new ExecutableFinder();

        if (null !== $symfonyPath = $finder->find('symfony')) {
            $definition->addMethodCall('add', ['symfony_console', 'symfony', 'console', $symfonyPath]);
            $definition->addMethodCall('add', ['symfony_bin', 'symfony', null, $symfonyPath]);

            return;
        }

        if (null !== $phpPath = $finder->find('php')) {
            $definition->addMethodCall('add', ['symfony_console', 'php', 'bin/console', $phpPath]);
        }
    }

}
