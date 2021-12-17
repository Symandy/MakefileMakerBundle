<?php

declare(strict_types=1);

namespace Symandy\Tests\MakefileMakerBundle\Unit\Model;

use PHPUnit\Framework\TestCase;
use Symandy\MakefileMakerBundle\Model\Command;
use Symandy\MakefileMakerBundle\Model\Instruction;

final class CommandTest extends TestCase
{

    public function testSetProperties(): Command
    {
        $command = new Command('test-command', 'description test command');

        self::assertSame('test-command', $command->getName());
        self::assertSame('description test command', $command->getDescription());

        return $command;
    }

    /**
     * @depends testSetProperties
     */
    public function testAddInstructions(Command $command): void
    {
        self::assertSame(0, count($command->getInstructions()));

        $instruction = new Instruction('test-instruction');
        $command->addInstruction($instruction);

        self::assertSame(1, count($command->getInstructions()));

        $instructions = [$instruction];
        self::assertSame($instructions, $command->getInstructions());
    }

}
