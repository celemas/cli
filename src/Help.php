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

	/**
	 * @param list<Opt> $opts
	 * @param list<Arg> $arguments
	 */
	public function show(Command $meta, array $opts = [], array $arguments = []): void
	{
		$script = $_SERVER['argv'][0] ?? '';

		if ($meta->description !== '') {
			$label = $this->output->color('Description:', 'brown') . "\n";
			$this->output->echo("{$label}  {$meta->description}\n\n");
		}

		$usage = $this->output->color('Usage:', 'brown') . "\n  php {$script} {$meta->full()}";

		foreach ($arguments as $argument) {
			$usage .= $argument->optional ? " [<{$argument->name}>]" : " <{$argument->name}>";
		}

		$this->output->echo($usage . ($opts === [] ? "\n" : " [options]\n"));
		$this->showArguments($arguments);
		$this->showOptions($opts);
	}

	/** @param list<Arg> $arguments */
	private function showArguments(array $arguments): void
	{
		if ($arguments === []) {
			return;
		}

		$this->output->echo("\n" . $this->output->color('Arguments:', 'brown') . "\n");

		foreach ($arguments as $argument) {
			$this->output->echo('    ' . $this->output->color("<{$argument->name}>", 'green') . "\n");
			$this->output->echo($this->output->indent($argument->description, 8, 80) . "\n");
		}
	}

	/** @param list<Opt> $opts */
	private function showOptions(array $opts): void
	{
		if ($opts === []) {
			return;
		}

		$this->output->echo("\n" . $this->output->color('Options:', 'brown') . "\n");

		foreach ($opts as $opt) {
			$suffix = match (true) {
				$opt->value === '' => '',
				$opt->optionalValue => "[=<{$opt->value}>]",
				default => "=<{$opt->value}>",
			};

			$option = $opt->short === ''
				? $opt->long . $suffix
				: "{$opt->short}{$suffix}, {$opt->long}{$suffix}";

			$description = $opt->default === ''
				? $opt->description
				: "{$opt->description} [default: {$opt->default}]";

			$this->output->echo('    ' . $this->output->color($option, 'green') . "\n");
			$this->output->echo($this->output->indent($description, 8, 80) . "\n");
		}
	}

	/**
	 * Renders help for a command instance or class from its attributes.
	 *
	 * @param class-string|object $command
	 */
	public function showFor(object|string $command): void
	{
		$this->show(Command::of($command), Opt::of($command), Arg::of($command));
	}
}
