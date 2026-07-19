<?php

declare(strict_types=1);

namespace Celema\Console\Tests\Fixtures;

use Celema\Console\Args;
use Celema\Console\Command;
use Celema\Console\Opt;
use Celema\Console\Output;

#[Command('help:variants', 'Exercises help option rendering')]
#[Opt('--verbose', 'Enable verbose output', short: '-v')]
#[Opt('--prune', 'Drop obsolete entries')]
#[Opt('--host', 'Host to bind to', short: '-h', value: 'host')]
#[Opt('--release', 'Install a specific tag', value: 'tag')]
#[Opt('--watch', 'Optionally watch files', short: '-w', value: 'file', optionalValue: true)]
class HelpVariants
{
	public function __invoke(Args $args, Output $output): int
	{
		return 0;
	}
}
