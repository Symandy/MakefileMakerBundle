<?php

declare(strict_types=1);

namespace Symandy\Tests\MakefileMakerBundle\Unit\Model;

use PHPUnit\Framework\TestCase;
use Symandy\MakefileMakerBundle\Model\Executable;
use Symandy\MakefileMakerBundle\Model\Instruction;

final class InstructionTest extends TestCase
{

    public function testSetProperties(): Instruction
    {
        $instruction = new Instruction('test-instruction');

        self::assertSame('test-instruction', $instruction->getName());

        $executable = new Executable('name-test', 'test', 'console', '/path/to/test/dir');
        $instruction->setExecutable($executable);

        self::assertSame($executable, $instruction->getExecutable());

        return $instruction;
    }

    /**
     * @depends testSetProperties
     */
    public function testAddArguments(Instruction $instruction): Instruction
    {
        $arguments = ['test-argument'];

        self::assertSame(0, count($instruction->getArguments()));

        $instruction->addArgument('test-argument');

        self::assertSame(1, count($instruction->getArguments()));
        self::assertSame($arguments, $instruction->getArguments());

        return $instruction;
    }

    /**
     * @depends testSetProperties
     */
    public function testAddOptions(Instruction $instruction): Instruction
    {
        $options = ['--test-option' => 'ok'];

        self::assertSame(0, count($instruction->getOptions()));

        $instruction->addOption('--test-option', 'ok');

        self::assertSame(1, count($instruction->getOptions()));
        self::assertSame($options, $instruction->getOptions());
        self::assertSame('ok', $instruction->getOption('--test-option'));

        return $instruction;
    }

    /**
     * @depends testAddArguments
     */
    public function testFormatArguments(Instruction $instruction): Instruction
    {
        self::assertSame('test-argument', $instruction->formatArguments());

        $instruction->addArgument('test-argument-2');

        self::assertSame('test-argument test-argument-2', $instruction->formatArguments());

        return $instruction;
    }

    /**
     * @depends testSetProperties
     */
    public function testFormatOptions(Instruction $instruction): Instruction
    {
        self::assertSame('--test-option ok', $instruction->formatOptions());

        $instruction->addOption('--test-empty-option', null);

        self::assertSame('--test-option ok --test-empty-option', $instruction->formatOptions());

        return $instruction;
    }

    /**
     * @depends testFormatArguments
     * @depends testFormatOptions
     */
    public function testFormatArgumentsAndOptions(Instruction $instruction): void
    {
        self::assertSame(
            'test-argument test-argument-2 --test-option ok --test-empty-option',
            $instruction->formatArgumentsAndOptions()
        );


        $instruction = new Instruction('test-instruction-2');

        self::assertSame('', $instruction->formatArgumentsAndOptions());
    }

}
