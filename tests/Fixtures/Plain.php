<?php

declare(strict_types=1);

namespace Celemas\Cli\Tests\Fixtures;

use Celemas\Cli\Args;
use Celemas\Cli\Command;

class Plain extends Command
{
	protected string $name = 'plain';
	protected string $description = 'An ungrouped command';

	public function run(Args $args): int
	{
		$this->echo('Plain');

		return self::SUCCESS;
	}
}
