<?php

declare(strict_types=1);

namespace Celemas\Cli\Tests\Fixtures;

use Celemas\Cli\Args;
use Celemas\Cli\Command;

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
