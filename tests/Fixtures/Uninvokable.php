<?php

declare(strict_types=1);

namespace Celema\Console\Tests\Fixtures;

use Celema\Console\Command;

#[Command('broken', 'Cannot be invoked')]
class Uninvokable {}
