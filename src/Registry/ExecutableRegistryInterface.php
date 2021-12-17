<?php

declare(strict_types=1);

namespace Symandy\MakefileMakerBundle\Registry;

use Symandy\MakefileMakerBundle\Model\Executable;

interface ExecutableRegistryInterface
{

    /**
     * @return array<string, Executable>
     */
    public function all(): array;

    public function get(string $executable): ?Executable;

    public function add(string $name, string $filename, ?string $output, string $path): void;

}
