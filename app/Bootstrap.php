<?php

declare(strict_types=1);

namespace App;

require_once __DIR__ . '/../lib/Application/Functions.php';

use Nette\Configurator;

class Bootstrap
{
	public static function boot(): Configurator
	{
		
		$configurator = new Configurator;

		//$configurator->setDebugMode('23.75.345.200'); // enable for your remote IP
		$configurator->enableTracy(__DIR__ . '/../log');

		$configurator->setTimeZone('Europe/Prague');
		$configurator->setTempDirectory(__DIR__ . '/../temp');
		$configurator->addParameters([
			'nodeModules' => __DIR__ . '/../node_modules'
		]);
		$configurator->createRobotLoader()
			->addDirectory(__DIR__)
			->addDirectory(__DIR__ . '/../lib')
			->register();

		$configurator->addConfig(__DIR__ . '/config/common.neon');
		$configurator->addConfig(__DIR__ . '/config/local.neon');

		return $configurator;
	}
}
