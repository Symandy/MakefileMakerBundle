<?php

declare(strict_types=1);

namespace Symandy\MakefileMakerBundle;

use Symandy\MakefileMakerBundle\DependencyInjection\Compiler\AddSymfonyConsoleExecutablePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class SymandyMakefileMakerBundle extends Bundle
{

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new AddSymfonyConsoleExecutablePass());
    }

}
