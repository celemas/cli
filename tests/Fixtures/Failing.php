<?php

declare(strict_types=1);

namespace Celemas\Cli\Tests\Fixtures;

use Celemas\Cli\Args;
use Celemas\Cli\Command;

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
