<?php

declare(strict_types=1);

namespace Symandy\MakefileMakerBundle\Builder;

use Symandy\MakefileMakerBundle\Model\Group;

interface GroupBuilderInterface
{

    public function build(string $name, array $config): Group;

}
