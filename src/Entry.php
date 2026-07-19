<?php

declare(strict_types=1);

namespace Celema\Console;

use Closure;
use ValueError;

/**
 * A registered command: its metadata plus the factory producing the
 * runnable instance or closure on first use.
 *
 * @internal
 */
final class Entry
{
	private ?object $command = null;

	/** @param class-string|null $class */
	private function __construct(
		public readonly Command $meta,
		private readonly ?string $class,
		private readonly Closure $factory,
	) {}

	public static function fromInstance(object $command): self
	{
		return new self(Command::of($command), $command::class, static fn(): object => $command);
	}

	/** @param class-string $class */
	public static function fromClass(string $class): self
	{
		return new self(Command::of($class), $class, static fn(): object => new $class());
	}

	/** @param class-string $class */
	public static function fromFactory(string $class, Closure $factory): self
	{
		return new self(Command::of($class), $class, $factory);
	}

	public static function fromClosure(string $name, string $description, Closure $command): self
	{
		return new self(new Command($name, $description), null, static fn(): object => $command);
	}

	public function command(): object
	{
		if ($this->command === null) {
			$command = ($this->factory)();

			if (!is_object($command)) {
				throw new ValueError("Factory for command '{$this->meta->full()}' must return an object");
			}

			$this->command = $command;
		}

		return $this->command;
	}

	/** @return list<Opt> */
	public function opts(): array
	{
		return $this->class === null ? [] : Opt::of($this->class);
	}

	/** @return list<Arg> */
	public function args(): array
	{
		return $this->class === null ? [] : Arg::of($this->class);
	}
}
