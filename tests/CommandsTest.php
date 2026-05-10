<?php

declare(strict_types=1);

namespace Celemas\Cli\Tests;

use Celemas\Cli\Commands;
use Celemas\Cli\Tests\Fixtures\BarStuff;
use Celemas\Cli\Tests\Fixtures\Erring;
use Celemas\Cli\Tests\Fixtures\FooDrivel;
use Celemas\Cli\Tests\Fixtures\FooStuff;

class CommandsTest extends TestCase
{
	public function testInitEmptyThenAddOneCommand(): void
	{
		$commands = new Commands();
		$foo = new FooStuff();
		$commands->add($foo);

		$this->assertSame($foo, $commands->get()[0]);
	}

	public function testInitWithOneCommand(): void
	{
		$foo = new FooStuff();
		$commands = new Commands($foo);

		$this->assertSame($foo, $commands->get()[0]);
	}

	public function testInitWithOneCommandThenAddAnotherCommand(): void
	{
		$foo = new FooStuff();
		$commands = new Commands($foo);
		$bar = new BarStuff();
		$commands->add($bar);

		$this->assertSame($foo, $commands->get()[0]);
		$this->assertSame($bar, $commands->get()[1]);
	}

	public function testInitWithArray(): void
	{
		$foo = new FooStuff();
		$bar = new BarStuff();
		$commands = new Commands([$foo, $bar]);

		$this->assertSame($foo, $commands->get()[0]);
		$this->assertSame($bar, $commands->get()[1]);
	}

	public function testInitWithArrayThenAddAnotherCommand(): void
	{
		$foo = new FooStuff();
		$bar = new BarStuff();
		$commands = new Commands([$foo, $bar]);
		$drivel = new FooDrivel();
		$commands->add($drivel);

		$this->assertSame($foo, $commands->get()[0]);
		$this->assertSame($bar, $commands->get()[1]);
		$this->assertSame($drivel, $commands->get()[2]);
	}

	public function testInitWithArrayThenAddAnotherArray(): void
	{
		$foo = new FooStuff();
		$bar = new BarStuff();
		$commands = new Commands([$foo, $bar]);
		$drivel = new FooDrivel();
		$err = new Erring();
		$commands->add([$drivel, $err]);

		$this->assertSame($foo, $commands->get()[0]);
		$this->assertSame($bar, $commands->get()[1]);
		$this->assertSame($drivel, $commands->get()[2]);
		$this->assertSame($err, $commands->get()[3]);
	}

	public function testInitWithOneCommandThenAddCommands(): void
	{
		$foo = new FooStuff();
		$commands = new Commands($foo);
		$drivel = new FooDrivel();
		$err = new Erring();
		$commands->add(new Commands([$drivel, $err]));

		$this->assertSame($foo, $commands->get()[0]);
		$this->assertSame($drivel, $commands->get()[1]);
		$this->assertSame($err, $commands->get()[2]);
	}

	public function testInitWithArrayThenAddCommands(): void
	{
		$foo = new FooStuff();
		$bar = new BarStuff();
		$commands = new Commands([$foo, $bar]);
		$drivel = new FooDrivel();
		$err = new Erring();
		$commands->add(new Commands([$drivel, $err]));

		$this->assertSame($foo, $commands->get()[0]);
		$this->assertSame($bar, $commands->get()[1]);
		$this->assertSame($drivel, $commands->get()[2]);
		$this->assertSame($err, $commands->get()[3]);
	}
}
