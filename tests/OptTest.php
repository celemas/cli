<?php

declare(strict_types=1);

namespace Celema\Console\Tests;

use Celema\Console\Opt;
use PHPUnit\Framework\Attributes\DataProvider;
use ValueError;

class OptTest extends TestCase
{
	public function testValidDeclarations(): void
	{
		$plain = new Opt('--force', 'A flag');
		$full = new Opt('--watch', 'Files', short: '-w', value: 'file', optionalValue: true);

		$this->assertSame('--force', $plain->long);
		$this->assertSame('-w', $full->short);
	}

	public static function invalidLongProvider(): array
	{
		return [['force'], ['-f'], ['--'], ['---x'], ['--with=value'], ['--with space']];
	}

	#[DataProvider('invalidLongProvider')]
	public function testRejectInvalidLongName(string $long): void
	{
		$this->expectException(ValueError::class);
		$this->expectExceptionMessage("Invalid option name '{$long}'");

		new Opt($long, 'Broken');
	}

	public static function invalidShortProvider(): array
	{
		return [['w'], ['-'], ['--w'], ['-w=x'], ['-w space']];
	}

	#[DataProvider('invalidShortProvider')]
	public function testRejectInvalidShortName(string $short): void
	{
		$this->expectException(ValueError::class);
		$this->expectExceptionMessage("Invalid short option name '{$short}'");

		new Opt('--watch', 'Files', short: $short);
	}

	public function testRejectOptionalValueWithoutValueLabel(): void
	{
		$this->expectException(ValueError::class);
		$this->expectExceptionMessage(
			"Option '--watch' declares an optional value without a value label",
		);

		new Opt('--watch', 'Files', optionalValue: true);
	}
}
