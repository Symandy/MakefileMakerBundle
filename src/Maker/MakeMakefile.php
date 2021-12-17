<?php

declare(strict_types=1);

namespace Symandy\MakefileMakerBundle\Maker;

use Symandy\MakefileMakerBundle\Model\Command;
use Symandy\MakefileMakerBundle\Model\Group;
use Symandy\MakefileMakerBundle\Provider\GroupProviderInterface;
use Symandy\MakefileMakerBundle\Registry\ExecutableRegistryInterface;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Filesystem\Filesystem;

final class MakeMakefile extends AbstractMaker
{

    private const TEMPLATE_PATH = __DIR__ . '/../Resources/skeleton/makefile/Makefile.tpl.php';

    private const CHOICE_ALL = 'all';

    private string $projectDirectory;

    private ExecutableRegistryInterface $executableRegistry;

    private Filesystem $filesystem;

    private GroupProviderInterface $provider;

    /** @var array<int, Group> */
    private array $groups = [];

    public function __construct(
        string $projectDirectory,
        ExecutableRegistryInterface $executableRegistry,
        Filesystem $filesystem,
        GroupProviderInterface $provider
    ) {
        $this->filesystem = $filesystem;
        $this->executableRegistry = $executableRegistry;
        $this->projectDirectory = $projectDirectory;
        $this->provider = $provider;
    }

    public static function getCommandName(): string
    {
        return 'make:makefile';
    }

    public static function getCommandDescription(): string
    {
        return 'Creates a makefile for Symfony commands';
    }

    public function configureCommand(SymfonyCommand $command, InputConfiguration $inputConfig): void
    {
        $command
            ->addOption(
                'override',
                'o',
                InputOption::VALUE_NONE,
                'Override existing Makefile',
            )
        ;
    }

    public function configureDependencies(DependencyBuilder $dependencies): void
    {
    }

    public function interact(InputInterface $input, ConsoleStyle $io, SymfonyCommand $command): void
    {
        /** @var QuestionHelper $helper */
        $helper = $command->getHelper('question');

        $groups = $this->provider->provideAvailableGroups();

        $question = new ChoiceQuestion(
            'Which groups do you want to import ?',
            array_merge([self::CHOICE_ALL], $groups)
        );
        $question->setMultiselect(true);

        /** @var array<int, Group> $ */
        $groupAnswers = $helper->ask($input, $io, $question);

        if (in_array(self::CHOICE_ALL, $groupAnswers)) {
            $groupAnswers = $groups;
        }

        $question = new ConfirmationQuestion(
            'Do you want to modify which commands to import ? (Y/n) [default: no]',
            false
        );
        if (!$helper->ask($input, $io, $question)) {
            $this->groups = $groupAnswers;

            return;
        }

        foreach ($groupAnswers as $group) {
            $question = new ChoiceQuestion(
                sprintf('Which commands do you want to import from group "%s"', $group->getName()),
                array_merge([self::CHOICE_ALL], $group->getCommands())
            );
            $question->setMultiselect(true);

            /** @var array<int, Command> $commandAnswers */
            $commandAnswers = $helper->ask($input, $io, $question);

            if ([] === $commandAnswers) {
                continue;
            }

            if (in_array(self::CHOICE_ALL, $commandAnswers)) {
                $commandAnswers = $group->getCommands();
            }

            $tmpGroup = new Group($group->getName());

            foreach ($commandAnswers as $command) {
                $tmpGroup->addCommand($command);
            }

            $this->groups[] = $tmpGroup;
        }
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator): void
    {
        $filepath = sprintf('%s/Makefile', $this->projectDirectory);

        if ($input->getOption('override') && $this->filesystem->exists($filepath)) {
            $this->filesystem->remove($filepath);
        }

        $generator->generateFile($filepath, self::TEMPLATE_PATH, [
            'executables' => $this->executableRegistry->all(),
            'groups' => $this->groups
        ]);

        $generator->writeChanges();
    }

}
