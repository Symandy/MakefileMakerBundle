<?php

declare(strict_types=1);

namespace Symandy\MakefileMakerBundle\Provider;

use Symandy\MakefileMakerBundle\Builder\GroupBuilderInterface;

final class GroupProvider implements GroupProviderInterface
{

    private array $groupsDefinition;

    private GroupBuilderInterface $groupBuilder;

    public function __construct(
        GroupBuilderInterface $groupBuilder,
        array $groupsDefinition = []
    ) {
        $this->groupsDefinition = $groupsDefinition;
        $this->groupBuilder = $groupBuilder;
    }

    public function provideAvailableGroups(): array
    {
        $groups = [];

        foreach ($this->groupsDefinition as $name => $groupDefinition) {
            $groups[] = $this->groupBuilder->build($name, $groupDefinition);
        }

        return $groups;
    }


}
