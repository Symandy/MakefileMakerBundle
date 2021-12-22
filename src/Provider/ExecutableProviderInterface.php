<?php

declare(strict_types=1);

namespace Symandy\MakefileMakerBundle\Provider;

use Symandy\MakefileMakerBundle\Model\Executable;
use Symandy\MakefileMakerBundle\Model\Group;

interface ExecutableProviderInterface
{

    /**
     * @param array<int, Group> $groups
     * @return array<int, Executable>
     */
    public function provideUsedExecutables(array $groups): array;

}
