<?php

declare(strict_types=1);

namespace Symandy\MakefileMakerBundle\Builder;

use Symandy\MakefileMakerBundle\Model\Command;
use Symandy\MakefileMakerBundle\Model\Group;
use Symandy\MakefileMakerBundle\Model\Instruction;
use Symandy\MakefileMakerBundle\Registry\ExecutableRegistryInterface;

final class GroupBuilder implements GroupBuilderInterface
{

    private ExecutableRegistryInterface $executableRegistry;

    public function __construct(ExecutableRegistryInterface $executableRegistry)
    {
        $this->executableRegistry = $executableRegistry;
    }

    public function build(string $name, array $config): Group
    {
        $group = new Group($name);

        foreach ($config['commands'] ?? [] as $name => $commandConfig) {
            $group->addCommand($this->buildCommand($commandConfig['name'] ?? $name, $commandConfig));
        }

        return $group;
    }

    private function buildCommand(string $name, array $config): Command
    {
        $command = new Command($name);
        $command->setDescription($config['description']);

        foreach ($config['instructions'] as $instructionConfig) {
            $command->addInstruction($this->buildInstruction($instructionConfig));
        }

        return $command;
    }

    private function buildInstruction(array $config): Instruction
    {
        $instruction = new Instruction($config['name']);

        if (null !== $config['executable']) {
            $executable = $this->executableRegistry->get($config['executable']);

            $instruction->setExecutable($executable);
        }

        foreach ($config['arguments'] ?? [] as $argument) {
            $instruction->addArgument($argument);
        }

        foreach ($config['options'] ?? [] as ['key' => $key, 'value' => $value]) {
            if (str_starts_with($key, '-')) {
                $instruction->addOption($key, $value);
            } elseif (1 < strlen($key)) {
                $instruction->addOption(sprintf('--%s', $key), $value);
            } else {
                $instruction->addOption(sprintf('-%s', $key), $value);
            }
        }

        return $instruction;
    }

}
