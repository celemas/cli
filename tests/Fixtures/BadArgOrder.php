<?php

declare(strict_types=1);

namespace Celema\Console\Tests\Fixtures;

use Celema\Console\Arg;
use Celema\Console\Command;

#[Command('badargs', 'Declares arguments out of order')]
#[Arg('first', 'An optional argument', optional: true)]
#[Arg('second', 'A required argument')]
class BadArgOrder
{
	public function __invoke(): int
	{
		return 0;
	}
}
