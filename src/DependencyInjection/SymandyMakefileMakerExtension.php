<?php

declare(strict_types=1);

namespace Symandy\MakefileMakerBundle\DependencyInjection;

use Exception;
use InvalidArgumentException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Symfony\Component\Process\ExecutableFinder;

final class SymandyMakefileMakerExtension extends ConfigurableExtension implements
    PrependExtensionInterface,
    CompilerPassInterface
{

    private array $executables = [];

    /**
     * @throws Exception
     */
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config/'));
        $loader->load('services.xml');

        $definition = $container->getDefinition('symandy_makefile_maker.provider.group');
        $definition->setArgument('$groupsDefinition', $mergedConfig['groups']);

        $this->executables = $mergedConfig['executables'];
    }

    public function prepend(ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        try {
            $loader->load('symandy_makefile_maker.yaml');
        } catch (Exception $e) {
        }
    }

    public function process(ContainerBuilder $container): void
    {
        $finder = new ExecutableFinder();

        if (!$container->has('symandy_makefile_maker.registry.executable_registry')) {
            return;
        }

        $definition = $container->getDefinition('symandy_makefile_maker.registry.executable_registry');

        foreach ($this->executables as $executable) {
            $name = $executable['name'];
            $filename = $executable['filename'] ?? $name;
            $output = $executable['output'] ?? null;
            $path = $executable['path'] ?? $finder->find($filename);

            if (null === $path) {
                throw new InvalidArgumentException(sprintf('Could not find any "%s" executable ', $executable));
            }

            $definition->addMethodCall('add', [$name, $filename, $output, $path]);
        }
    }

}