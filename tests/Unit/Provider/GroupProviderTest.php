<?php

declare(strict_types=1);

namespace Symandy\Tests\MakefileMakerBundle\Unit\Provider;

use PHPUnit\Framework\TestCase;
use Symandy\MakefileMakerBundle\Builder\GroupBuilder;
use Symandy\MakefileMakerBundle\Model\Command;
use Symandy\MakefileMakerBundle\Model\Group;
use Symandy\MakefileMakerBundle\Model\Instruction;
use Symandy\MakefileMakerBundle\Provider\GroupProvider;
use Symandy\MakefileMakerBundle\Registry\ExecutableRegistryInterface;

class GroupProviderTest extends TestCase
{

    public function testProvideGroups(): void
    {
        $executableRegistry = $this->getMockBuilder(ExecutableRegistryInterface::class)->getMock();
        $executableRegistry->add(
            'test-executable',
            'test',
            'console',
            '/path/to/test/dir'
        );

        $groupBuilder = new GroupBuilder($executableRegistry);
        $groupProvider = new GroupProvider($groupBuilder, $this->provideConfiguration());

        $groups = $groupProvider->provideAvailableGroups();

        self::assertEquals($this->getExpectedGroups($executableRegistry), $groups);
    }

    private function provideConfiguration(): array
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
                    ],
                    't2' => [
                        'name' => 'test-command-2',
                        'description' => 'description test command 2',
                        'instructions' => [
                            [
                                'executable' => 'test-executable',
                                'name' => 'test-instruction-2',
                                'arguments' => ['test-argument', 'test-argument-2'],
                                'options' => []
                            ],
                            [
                                'executable' => null,
                                'name' => 'test-instruction-3',
                                'options' => [],
                                'arguments' => [],
                            ],
                            [
                                'executable' => 'test-executable',
                                'name' => 'test-instruction-4',
                                'options' => [
                                    ['key' => '--bar', 'value' => 'baz'],
                                ],
                                'arguments' => [],
                            ],
                        ],
                    ]
                ]
            ],
            'test-empty-group' => [
                'commands' => []
            ]
        ];
    }

    /**
     * @return array<int, Group>
     */
    private function getExpectedGroups(ExecutableRegistryInterface $executableRegistry): array
    {
        $group = new Group('test-group');

        $command = new Command('test-command', 'description test command');
        $instruction = new Instruction();
        $instruction->setExecutable($executableRegistry->get('test-executable'));
        $instruction->setName('test-instruction');
        $instruction->addArgument('test-argument');
        $instruction->addOption('--test-option', 'test-first-value');
        $instruction->addOption('-o', 'test-second-value');
        $instruction->addOption('--foo', null);

        $command->addInstruction($instruction);
        $group->addCommand($command);

        $command2 = new Command('test-command-2','description test command 2');

        $instruction2 = new Instruction();
        $instruction2->setExecutable($executableRegistry->get('test-executable'));
        $instruction2->setName('test-instruction-2');
        $instruction2->addArgument('test-argument');
        $instruction2->addArgument('test-argument-2');

        $instruction3 = new Instruction();
        $instruction3->setName('test-instruction-3');

        $instruction4 = new Instruction();
        $instruction4->setExecutable($executableRegistry->get('test-executable'));
        $instruction4->setName('test-instruction-4');
        $instruction4->addOption('--bar', 'baz');

        $command2->addInstruction($instruction2);
        $command2->addInstruction($instruction3);
        $command2->addInstruction($instruction4);
        $group->addCommand($command2);

        $emptyGroup = new Group('test-empty-group');

        return [$group, $emptyGroup];
    }

}
