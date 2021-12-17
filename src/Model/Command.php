<?php

declare(strict_types=1);

namespace Symandy\MakefileMakerBundle\Model;

final class Command
{

    private ?string $name;

    private ?string $description;

    /** @var array<int, Instruction>  */
    private array $instructions = [];

    public function __construct(string $name = null, string $description = null)
    {
        $this->name = $name;
        $this->description = $description;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return array<int, Instruction>
     */
    public function getInstructions(): array
    {
        return $this->instructions;
    }

    public function addInstruction(Instruction $instruction): void
    {
        $this->instructions[] = $instruction;
    }

    public function __toString(): string
    {
        return $this->getName() ?? '';
    }

}
