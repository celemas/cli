<?php

declare(strict_types=1);

namespace Celema\Console;

use Attribute;
use ReflectionAttribute;
use ReflectionClass;
use ValueError;

/**
 * Describes one option of a command in its help output.
 *
 * Give the flag names and, for value-taking options, a `value` label; the
 * `--opt=<value>` notation is rendered by the runner so it cannot drift from
 * the `=`-only parser. Omit `value` for a boolean flag; set `optionalValue`
 * for a flag whose value is optional (`--opt[=<value>]`). A `default`
 * renders as `[default: ...]` after the description.
 *
 * @api
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final class Opt
{
	// Attribute parameters are passed as named arguments; a parameter
	// object would defeat the attribute syntax.
	// @mago-expect lint:excessive-parameter-list
	public function __construct(
		public readonly string $long,
		public readonly string $description,
		public readonly string $short = '',
		public readonly string $value = '',
		public readonly bool $optionalValue = false,
		public readonly string $default = '',
	) {
		if (preg_match('/^--[^-=\s][^=\s]*$/', $long) !== 1) {
			throw new ValueError("Invalid option name '{$long}'");
		}

		if ($short !== '' && preg_match('/^-[^-=\s][^=\s]*$/', $short) !== 1) {
			throw new ValueError("Invalid short option name '{$short}'");
		}

		if ($optionalValue && $value === '') {
			throw new ValueError("Option '{$long}' declares an optional value without a value label");
		}
	}

	/**
	 * Reads all option attributes off a command instance or class.
	 *
	 * @param class-string|object $command
	 * @return list<self>
	 */
	public static function of(object|string $command): array
	{
		$class = is_object($command) ? $command::class : $command;

		return array_map(
			static fn(ReflectionAttribute $attribute): self => $attribute->newInstance(),
			new ReflectionClass($class)->getAttributes(self::class),
		);
	}
}
