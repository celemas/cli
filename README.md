# Celemas Console

<!-- prettier-ignore-start -->
[![ci](https://codeberg.org/celemas/cli/badges/workflows/ci.yml/badge.svg?style=flat&logo=codeberg&logoColor=white&label=ci)](https://codeberg.org/celemas/cli/actions)
[![code coverage](https://img.shields.io/endpoint?url=https%3A%2F%2Fcov.celemas.dev%2Fcelemas%2Fcli%2Fcode%2Fbadge.json)](https://cov.celemas.dev/celemas/cli/code)
[![type coverage](https://img.shields.io/endpoint?url=https%3A%2F%2Fcov.celemas.dev%2Fcelemas%2Fcli%2Ftypes%2Fbadge-cover.json)](https://cov.celemas.dev/celemas/cli/types)
[![psalm level](https://img.shields.io/endpoint?url=https%3A%2F%2Fcov.celemas.dev%2Fcelemas%2Fcli%2Ftypes%2Fbadge-level.json)](https://cov.celemas.dev/celemas/cli/types)
[![Software License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE.md)
<!-- prettier-ignore-end -->

A command line interface helper.

## Features

- Simple command creation with automatic help generation
- Parsed options and positional arguments via an injected `Args` object
- Built-in color support for terminal output
- Command help with `php run help <command>`
- Built-in `commands` command for shell autocomplete
- `--key=value` options (repeatable) and boolean `--flag` / `-h` flags
- Output helpers: `info()`, `success()`, `warn()`, `error()`, `echoln()` (warnings and errors go to STDERR)
- Text indentation and wrapping with `indent()`
- Debug mode for detailed error traces
- 100% test coverage

## Installation

```bash
composer require celemas/console
```

## Quick Start

Create a command by extending `Celemas\Console\Command`:

```php
use Celemas\Console\{Args, Command};

class MyCommand extends Command {
    protected string $name = 'mycommand';
    protected string $group = 'MyGroup';
    protected string $description = 'This is my command';

    public function run(Args $args): int
    {
        $name = $args->positional(0, 'world');
        $this->info("Running my command for {$name}");
        $this->success("Command completed!");

        return self::SUCCESS;
    }
}
```

Options use `--key=value` (a bare `--flag` is a boolean); every other argument is a positional. Read them from the injected `Args`:

```php
$name = $args->positional(0);        // first positional, or null
$conn = $args->opt('--conn', 'sqlite'); // option value, or the default
$force = $args->has('--force');      // boolean flag
```

Create a runner script and pass its exit code to `exit()`:

```php
<?php
require __DIR__ . '/vendor/autoload.php';

use Celemas\Console\{Runner, Commands};

$commands = new Commands([new MyCommand()]);
$runner = new Runner($commands);

exit($runner->run());
```

Run your command:

```bash
$ php run mycommand alice
Running my command for alice
Command completed!
```

## License

This project is licensed under the [MIT license](LICENSE.md).
