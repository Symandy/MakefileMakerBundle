<?php

declare(strict_types=1);

namespace Symandy\MakefileMakerBundle\Registry;

use Symandy\MakefileMakerBundle\Model\Executable;

final class ExecutableRegistry implements ExecutableRegistryInterface
{

    /** @var array<string, Executable> */
    private array $registry = [];

    /**
     * @return array<string, Executable>
     */
    public function all(): array
    {
        return $this->registry;
    }

    public function get(string $executable): ?Executable
    {
        return $this->registry[$executable] ?? null;
    }

    public function add(string $name, string $filename, ?string $output, string $path): void
    {
        $this->registry[$name] = new Executable($name, $filename, $output, $path);
    }

}
