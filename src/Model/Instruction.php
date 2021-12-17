<?php

declare(strict_types=1);

namespace Symandy\MakefileMakerBundle\Model;

final class Instruction
{

    private ?Executable $executable = null;

    private ?string $name;

    /** @var array<int, string> */
    private array $arguments = [];

    /** @var array<string, string|null> */
    private array $options = [];

    public function __construct(string $name = null)
    {
        $this->name = $name;
    }

    public function getExecutable(): ?Executable
    {
        return $this->executable;
    }

    public function setExecutable(?Executable $executable): void
    {
        $this->executable = $executable;
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
     * @return array<int, string>
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    public function addArgument(string $argument): void
    {
        $this->arguments[] = $argument;
    }

    /**
     * @return array<string, string|null>
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    public function getOption(string $key): ?string
    {
        return $this->options[$key] ?? null;
    }

    public function addOption(string $key, ?string $option): void
    {
        $this->options[$key] = $option;
    }

    public function formatArguments(): string
    {
        return implode(' ', $this->arguments);
    }

    public function formatOptions(): string
    {
        $options = '';

        if ([] === $this->options) {
            return $options;
        }

        $lastKey = array_key_last($this->options);

        foreach ($this->options as $key => $value) {
            $options .= null !== $value ? ($key . ' ' . $value) : $key;

            if ($lastKey !== $key) {
                $options .= ' ';
            }
        }

        return $options;
    }

    public function formatArgumentsAndOptions(): string
    {
        if ([] === $this->arguments) {
            return $this->formatOptions();
        }

        if ([] === $this->options) {
            return $this->formatArguments();
        }

        return sprintf('%s %s', $this->formatArguments(), $this->formatOptions());
    }

}
