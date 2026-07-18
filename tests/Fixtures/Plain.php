<?php

declare(strict_types=1);

namespace Celemas\Console\Tests\Fixtures;

use Celemas\Console\Args;
use Celemas\Console\Command;

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
