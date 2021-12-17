<?php

declare(strict_types=1);

namespace Symandy\MakefileMakerBundle\Model;

final class Group
{

    private string $name;

    /** @var array<int, Command>  */
    private array $commands = [];

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array<int, Command>
     */
    public function getCommands(): array
    {
        return $this->commands;
    }

    public function addCommand(Command $command): void
    {
        $this->commands[] = $command;
    }

    public function __toString(): string
    {
        return $this->getName();
    }

}
