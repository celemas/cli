---
title: Introduction
---

Celema Console is a command line interface helper like [Laravel's Artisan](https://laravel.com/docs/9.x/artisan) with way less magic.

## Installation

```bash
composer require celema/console
```

## Quick Start

A command is a plain class with a `#[Command]` attribute and an `__invoke()` method receiving the parsed arguments and the output:

```php
use Celema\Console\{Args, Command, Opt, Output};

// The first argument is the name by which the command is invoked from the
// command line. An optional `grp:` prefix namespaces the command and groups
// it in the help overview; `group` overrides the displayed group title.
#[Command('grp:mycommand', 'This is my command description', group: 'My Group')]
// Optional: each #[Opt] describes one option in the command's help text
// (e.g. `php run help mycommand`).
#[Opt('--stuff', 'Description of --stuff', short: '-s', value: 'stuff')]
#[Opt('--verbose', 'Enable verbose output', short: '-v')]
class MyCommand
{
    public function __invoke(Args $args, Output $out): int
    {
        $out->echo("Run my command\n");

        // Read options and positionals from the injected Args
        $name = $args->positional(0, 'world');   // first positional, or default
        $conn = $args->opt('--conn', 'sqlite');  // --conn=value, or default
        $force = $args->has('--force');          // boolean flag

        // Output helpers with color support (warn/error go to STDERR)
        $out->info('Informational message');
        $out->success('Success message');
        $out->warn('Warning message');
        $out->error('Error message');

        // echoln adds a newline automatically
        $out->echoln('Message with automatic newline');

        return 0;
    }
}
```

The constructor is yours: take whatever dependencies the command needs and register an instance or a factory (see below).

## Features

### Registering Commands

`Commands` accepts instances, class-strings, lazy factories, and named closures:

```php
use Celema\Console\{Args, Commands, Output};

$commands = new Commands([
    new MyCommand(),                          // instance
    Simple::class,                            // zero-argument constructor
    Expensive::class => fn() => new Expensive($db), // lazy factory
]);

// A closure as a lightweight one-off command
$commands->add('cache:clear', 'Clears the cache', function (Args $args, Output $out): int {
    // ...
    return 0;
});
```

Class-based commands carry their metadata in the `#[Command]` attribute, which is read without instantiating the class. Factories run only when their command is actually invoked — listing the help never constructs a command.

### Output Methods

- `echo(string $message, string $color = '', string $background = '')` - Output text
- `echoln(string $message, string $color = '', string $background = '')` - Output text with newline
- `info(string $message)` - Output informational message
- `success(string $message)` - Output success message (green)
- `warn(string $message)` - Output warning message (yellow, to STDERR)
- `error(string $message)` - Output error message (red, to STDERR)
- `color(string $text, string $color, string $background = '')` Return colored text
- `indent(string $text, int $indent, ?int $max = null)` Indent and wrap text

### Available Colors

Foreground: `black`, `gray`/`grey`, `red`, `lightred`, `green`, `lightgreen`, `brown`, `yellow`, `blue`, `lightblue`, `purple`, `lightpurple`, `magenta`, `lightmagenta`, `cyan`, `lightcyan`, `lightgray`/`lightgrey`, `white`

Background: `black`, `red`, `green`, `yellow`, `blue`, `purple`, `magenta`, `cyan`, `gray`/`grey`, `white`

### Command-Line Arguments

The Runner parses the command's arguments and passes them to `__invoke(Args $args, Output $out)`:

```bash
php run mycommand up --conn=sqlite --force
```

- `--key=value` sets an option; repeat the flag to collect multiple values.
- A dashed token without `=`, such as `--force` or `-h`, is a boolean flag.
- Every other token is a positional argument.

```php
$args->positional(0);            // "up" (or null / a default)
$args->positionals();            // ["up"]
$args->opt('--conn', 'pgsql');   // "sqlite" (or the default)
$args->opts('--tag');            // all values for a repeated option
$args->has('--force');           // true
```

A positional cannot start with `-` — such a token is read as a flag.

### Command Help

`php run help <command>` renders the description and usage line from the `#[Command]` attribute and one entry per `#[Opt]` attribute:

```php
#[Opt('--stuff', 'Description of --stuff', short: '-s', value: 'stuff')]
// Renders "-s=<stuff>, --stuff=<stuff>"
#[Opt('--verbose', 'Enable verbose output', short: '-v')]
// Renders "-v, --verbose"
#[Opt('--watch', 'Optionally watch files', value: 'file', optionalValue: true)]
// Renders "--watch[=<file>]"
```

### Built-in Commands

- `help` - Display help for all commands or a specific command
- `commands` - List all command names (useful for shell autocomplete)

The runner reserves no flags, so `--help`/`-h` (and every other flag) belong to your command; use `php run help <command>` for a command's help screen.

### Debug Mode

Enable debug mode in the Runner to display full stack traces when commands throw exceptions:

```php
$runner = new Runner($commands, debug: true);
```

Create a runner script, e. g. `run.php` or simply `run`:

```php
<?php

require __DIR__ . '/vendor/autoload.php';

use Celema\Console\{Runner, Commands};
use MyCommand;

$commands = new Commands([new MyCommand()]);

// Optional: enable debug mode to show stack traces on errors
$runner = new Runner($commands, debug: false);

exit($runner->run());
```

Run the command:

```bash
$ php run mycommand
Run my command

$ php run grp:mycommand
Run my command

$ php run help
Available commands:

My Group
    grp:mycommand  This is my command description

$ php run help mycommand
Help entry for my command

$ php run commands
List all available command names (useful for shell
autocomplete)
```
