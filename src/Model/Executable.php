<?php

declare(strict_types=1);

namespace Symandy\MakefileMakerBundle\Model;

final class Executable
{

    private string $name;

    private string $filename;

    private ?string $output;

    private string $path;

    public function __construct(string $name, string $filename, ?string $output, string $path)
    {
        $this->name = $name;
        $this->filename = $filename;
        $this->output = $output;
        $this->path = $path;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): void
    {
        $this->filename = $filename;
    }

    public function getOutput(): ?string
    {
        return $this->output;
    }

    public function setOutput(?string $output): void
    {
        $this->output = $output;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

}
