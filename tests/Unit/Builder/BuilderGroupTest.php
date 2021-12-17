<?php

declare(strict_types=1);

namespace Symandy\Tests\MakefileMakerBundle\Unit\Builder;

use PHPUnit\Framework\TestCase;
use Symandy\MakefileMakerBundle\Builder\GroupBuilder;
use Symandy\MakefileMakerBundle\Model\Command;
use Symandy\MakefileMakerBundle\Model\Group;
use Symandy\MakefileMakerBundle\Model\Instruction;
use Symandy\MakefileMakerBundle\Registry\ExecutableRegistryInterface;

final class BuilderGroupTest extends TestCase
{

    public function testBuild(): void
    {
        $executableRegistry = $this->getMockBuilder(ExecutableRegistryInterface::class)->getMock();
        $executableRegistry->add(
            'test-executable',
            'test',
            'console',
            '/path/to/test/dir'
        );

        $groupBuilder = new GroupBuilder($executableRegistry);

        $groupsConfig = $this->provideGroupsConfiguration();

        foreach ($groupsConfig as $groupName => $groupConfig) {
            $group = $groupBuilder->build($groupConfig['name'] ?? $groupName, $groupConfig);

            self::assertEquals($this->buildExpectedGroup($executableRegistry), $group);
        }

    }

    public function buildExpectedGroup(ExecutableRegistryInterface $executableRegistry): Group
    {
        $executable = $executableRegistry->get('test-executable');

        $group = new Group('test-group');

        $command = new Command('test-command', 'description test command');
        $group->addCommand($command);

        $instruction = new Instruction('test-instruction');
        $instruction->setExecutable($executable);
        $instruction->addArgument('test-argument');
        $instruction->addOption('--test-option', 'test-first-value');
        $instruction->addOption('-o', 'test-second-value');
        $instruction->addOption('--foo', null);

        $command->addInstruction($instruction);

        return $group;
    }

    public function provideGroupsConfiguration(): array
    {
        return [
            'test-group' => [
                'commands' => [
                    't1' => [
                        'name' => 'test-command',
                        'description' => 'description test command',
                        'instructions' => [
                            [
                                'executable' => 'test-executable',
                                'name' => 'test-instruction',
                                'arguments' => ['test-argument'],
                                'options' => [
                                    ['key' => 'test-option', 'value' => 'test-first-value'],
                                    ['key' => 'o', 'value' => 'test-second-value'],
                                    ['key' => '--foo', 'value' => null],
                                ]
                            ]
                        ],
                    ]
                ]
            ]
        ];
    }

}
