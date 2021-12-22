.DEFAULT_GOAL := help
<?php if (!isset($executables) || !isset($groups)) {
    throw new LogicException('Variables $executables and/or $groups are not defined'); }
?>

<?php foreach ($executables as $executable): ?>
<?php /** @var Symandy\MakefileMakerBundle\Model\Executable $executable */?>
<?= sprintf(
        "%s = %s %s\n",
        mb_strtoupper($executable->getName()),
        $executable->getPath(),
        null !== $executable->getOutput() ? $executable->getOutput() : ''
    ) ?>
<?php endforeach; ?>

<?php foreach ($groups as $group): ?>
<?php /** @var Symandy\MakefileMakerBundle\Model\Group $group */ ?>
<?php if ([] === $group->getCommands()) {
    continue;
} ?>
##
## <?= sprintf("%s\n", ucwords($group->getName())) ?>
.PHONY: <?= sprintf(
    "%s\n",
    implode(
        ' ',
        array_map(
            fn(Symandy\MakefileMakerBundle\Model\Command $command) => $command->getName(),
            $group->getCommands()
        )
    )
) ?>

<?php foreach ($group->getCommands() as $command): ?>
<?= sprintf("%s: ## %s\n", $command->getName(), $command->getDescription()) ?>
<?php foreach ($command->getInstructions() as $instruction): ?>
<?php if (null !== $executable = $instruction->getExecutable()): ?>
<?= sprintf(
    "\t@$(%s) %s %s\n",
    mb_strtoupper($executable->getName()),
    $instruction->getName(),
    $instruction->formatArgumentsAndOptions()
) ?>
<?php else: ?>
<?= sprintf("\t%s %s\n", $instruction->getName(), $instruction->formatArgumentsAndOptions()) ?>
<?php endif ?>
<?php endforeach; ?>

<?php endforeach; ?>

<?php endforeach; ?>
##
## Help
.PHONY: help

help: ## List of all commands
<?= "\t" ?>@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
