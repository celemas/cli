<?php

declare(strict_types=1);

namespace Celema\Console;

/**
 * Renders a command's help screen from its attributes.
 *
 * Used by the Runner for `help <command>`; commands that intercept a
 * `--help` flag themselves can render the same screen via `showFor()`.
 *
 * @api
 */
final class Help
{
	public function __construct(
		private readonly Output $output,
	) {}

	/** @param list<Opt> $opts */
	public function show(Command $meta, array $opts = []): void
	{
		$script = $_SERVER['argv'][0] ?? '';

		if ($meta->description !== '') {
			$label = $this->output->color('Description:', 'brown') . "\n";
			$this->output->echo("{$label}  {$meta->description}\n\n");
		}

		$usage = $this->output->color('Usage:', 'brown') . "\n  php {$script} {$meta->full()}";

		if ($opts === []) {
			$this->output->echo("{$usage}\n");

			return;
		}

		$this->output->echo("{$usage} [options]\n\n");
		$this->output->echoln($this->output->color('Options:', 'brown'));

		foreach ($opts as $opt) {
			$suffix = match (true) {
				$opt->value === '' => '',
				$opt->optionalValue => "[=<{$opt->value}>]",
				default => "=<{$opt->value}>",
			};

			$option = $opt->short === ''
				? $opt->long . $suffix
				: "{$opt->short}{$suffix}, {$opt->long}{$suffix}";

			$this->output->echo('    ' . $this->output->color($option, 'green') . "\n");
			$this->output->echo($this->output->indent($opt->description, 8, 80) . "\n");
		}
	}

	/**
	 * Renders help for a command instance or class from its attributes.
	 *
	 * @param class-string|object $command
	 */
	public function showFor(object|string $command): void
	{
		$this->show(Command::of($command), Opt::of($command));
	}
}
