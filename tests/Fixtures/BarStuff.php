<?php

declare(strict_types=1);

namespace Celemas\Console\Tests\Fixtures;

use Celemas\Console\Args;
use Celemas\Console\Command;

class BarStuff extends Command
{
	protected string $name = 'stuff';
	protected string $group = 'Bar';
	protected string $description = "Prints Bar's stuff to stdout";

	public function run(Args $args): int
	{
		$this->echo("Bar's stuff");

		return self::SUCCESS;
	}
}
