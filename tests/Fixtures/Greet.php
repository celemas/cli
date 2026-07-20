<?php

declare(strict_types=1);

namespace Celema\Console\Tests\Fixtures;

use Celema\Console\Arg;
use Celema\Console\Args;
use Celema\Console\Command;
use Celema\Console\Io;
use Celema\Console\Opt;

#[Command('greet', 'Greets a name')]
#[Arg('name', 'Who to greet', optional: true)]
#[Opt('--greeting', 'The greeting to use', value: 'greeting')]
class Greet
{
	public function __invoke(Args $args, Io $output): int
	{
		$name = $args->positional(0, 'World');
		$greeting = $args->opt('--greeting', 'Hello');
		$output->echo("{$greeting}, {$name}");

		return 0;
	}
}
