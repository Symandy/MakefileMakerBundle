<?php

declare(strict_types=1);

namespace Symandy\MakefileMakerBundle\Provider;

use Symandy\MakefileMakerBundle\Model\Group;

interface GroupProviderInterface
{

    /**
     * @return array<int, Group>
     */
    public function provideAvailableGroups(): array;

}
