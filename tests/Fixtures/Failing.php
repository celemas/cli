<?php

declare(strict_types=1);

namespace Celemas\Console\Tests\Fixtures;

use Celemas\Console\Args;
use Celemas\Console\Command;

class Failing extends Command
{
	protected string $name = 'fail';
	protected string $group = 'Foo';
	protected string $description = 'Returns a failure code';

	public function run(Args $args): int
	{
		return self::FAILURE;
	}
}
