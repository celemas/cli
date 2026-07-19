<?php

declare(strict_types=1);

namespace Celema\Console\Tests\Fixtures;

use Celema\Console\Args;
use Celema\Console\Command;
use Celema\Console\Output;

#[Command('foo:fail', 'Returns a failure code')]
class Failing
{
	public function __invoke(Args $args, Output $output): int
	{
		return 1;
	}
}
