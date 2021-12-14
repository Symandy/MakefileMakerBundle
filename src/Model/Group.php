<?php

declare(strict_types=1);

namespace Symandy\MakefileMakerBundle\Model;

final class Group
{

    private ?string $name;

    /** @var Command[]  */
    private array $commands = [];

    public function __construct(string $name = null)
    {
        $this->name = $name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return Command[]
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
