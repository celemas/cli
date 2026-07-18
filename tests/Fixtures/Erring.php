<?php

declare(strict_types=1);

namespace Celemas\Console\Tests\Fixtures;

use Celemas\Console\Args;
use Celemas\Console\Command;
use Exception;

class Erring extends Command
{
	protected string $name = 'err';
	protected string $group = 'Errors';
	protected string $prefix = 'err';
	protected string $description = 'Throws an error';

	public function run(Args $args): int
	{
		throw new Exception('Red herring');
	}
}
