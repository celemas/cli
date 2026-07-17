<?php

declare(strict_types=1);

namespace Celemas\Cli\Tests\Fixtures;

use Celemas\Cli\Args;
use Celemas\Cli\Command;

class Greet extends Command
{
	protected string $name = 'greet';
	protected string $group = 'Foo';
	protected string $description = 'Greets a name';

	public function run(Args $args): int
	{
		$name = $args->positional(0, 'World');
		$greeting = $args->opt('--greeting', 'Hello');
		$this->echo("{$greeting}, {$name}");

		return self::SUCCESS;
	}
}
