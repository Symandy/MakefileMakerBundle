<?php

declare(strict_types=1);

namespace Symandy\MakefileMakerBundle\Provider;

interface GroupProviderInterface
{

    public function provideAvailableGroups(): array;

}
