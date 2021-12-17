<?php

declare(strict_types=1);

namespace Symandy\Tests\MakefileMakerBundle\Unit\Model;

use PHPUnit\Framework\TestCase;
use Symandy\MakefileMakerBundle\Model\Command;
use Symandy\MakefileMakerBundle\Model\Group;

final class GroupTest extends TestCase
{

    public function test_set_properties(): Group
    {
        $group = new Group('test-group');

        self::assertSame('test-group', $group->getName());

        return $group;
    }

    /**
     * @depends test_set_properties
     */
    public function test_add_commands(Group $group): void
    {
        self::assertSame(0, count($group->getCommands()));

        $command = new Command('test-command', 'description test command');
        $group->addCommand($command);

        self::assertSame(1, count($group->getCommands()));

        $commands = [$command];
        self::assertSame($commands, $group->getCommands());
    }

}
