<?php declare(strict_types = 1);

namespace Adbros\Firebase\DI;

use Kreait\Firebase\Factory;
use Nette\DI\CompilerExtension;
use Nette\Schema\Expect;
use Nette\Schema\Schema;

class Extension extends CompilerExtension
{

	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'credentials' => Expect::anyOf(
				Expect::string(),
				Expect::array(),
			)->required(),
		]);
	}

	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();
		$config = (array) $this->getConfig();

		$builder->addDefinition($this->prefix('factory'))
			->setType(Factory::class)
			->setCreator(ExtensionFactory::class . '::create', [$config]);

		$builder->addDefinition($this->prefix('messaging'))
			->setCreator('@' . $this->prefix('factory') . '::createMessaging');
	}

}
