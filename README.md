# Symandy Makefile Maker Bundle

## Introduction

Symandy Makefile Maker is a Symfony bundle used to generate a Makefile containing Symfony commands shortcuts for a project

This bundle includes some basics commands (Symfony, Doctrine, Composer and more) but project commands can be added to this file.

## Installation

```shell
composer require --dev symandy/makefile-maker-bundle
```

If you are not using [Symfony Flex](https://github.com/symfony/flex) add the following lines to `config/bundles.php`

```php
<?php

return [
    ...
    Symandy\MakefileMakerBundle\SymandyMakefileMakerBundle::class => ['dev' => true, 'test' => true],
    ...
];
```

## Configuration

You can dump the full configuration by running the following command

```shell
php bin/console config:dump-reference SymandyMakefileMakerBundle
```

You can add `symandy_makefile_maker.yaml` file under `config/packages` directory to add additional commands to your Makefile. 

```yaml
symandy_makefile_maker:
  groups:
    your_project:
      commands:
        hello:
          name: hello
          description: Say hello
          instructions:
            - executable: symfony_console
              name: app:hello
              arguments: [John]
              options:
                - { key: last-name, value: Doe }
```

This configuration will output the following Makefile:

```shell
SYMFONY_CONSOLE = /usr/local/bin/symfony console
...

hello: ## Say hello
	@SYMFONY_CONSOLE dump-autoload John --last-name Doe 
```

Full configuration can be dumped using:

```shell
php bin/console debug:config SymandyMakefileMakerBundle
```

## Usage

You can now run this command by running: 

```shell
make hello
```

instead of:

```shell
symfony console app:hello John --last-name Doe 
```

## Note 

The only purpose of this bundle is to generate a Makefile for a Symfony project.
It can be removed if the Makefile won't be updated during development using:

```shell
composer remove --dev symandy/makefile-maker-bundle
```
