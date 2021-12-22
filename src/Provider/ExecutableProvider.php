<?php

declare(strict_types=1);

namespace Symandy\MakefileMakerBundle\Provider;

use Symandy\MakefileMakerBundle\Model\Command;
use Symandy\MakefileMakerBundle\Model\Executable;
use Symandy\MakefileMakerBundle\Model\Group;
use Symandy\MakefileMakerBundle\Model\Instruction;

final class ExecutableProvider implements ExecutableProviderInterface
{

    /**
     * @param array<int, Group> $groups
     * @return array<int, Executable>
     */
    public function provideUsedExecutables(array $groups): array
    {
        $executables = [];

        /** @var array<int, Command> $commands */
        $commands = [];

        foreach ($groups as $group) {
            $commands = array_merge($commands, $group->getCommands());
        }

        /** @var array<int, Instruction> $instructions */
        $instructions = [];

        foreach ($commands as $command) {
            $instructions = array_merge($instructions, $command->getInstructions());
        }

        foreach ($instructions as $instruction) {
            if (null === $executable = $instruction->getExecutable()) {
                continue;
            }

            if (!in_array($executable, $executables)) {
                $executables[] = $executable;
            }
        }

        return $executables;
    }

}
