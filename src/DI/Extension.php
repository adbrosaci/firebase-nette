<?php declare(strict_types = 1);

namespace Adbros\Firebase\DI;

use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Contract\DynamicLinks;
use Kreait\Firebase\Contract\Firestore;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Contract\RemoteConfig;
use Kreait\Firebase\Contract\Storage;
use Nette\DI\CompilerExtension;
use Nette\DI\ContainerBuilder;
use Nette\Schema\Expect;
use Nette\Schema\Schema;

class Extension extends CompilerExtension
{

	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'credentials' => Expect::anyOf(Expect::string(), Expect::array()),
			'autowired' => Expect::bool(true),
			'database_uri' => Expect::string(),
			'tenant_id' => Expect::string(),
			'default_dynamic_links_domain' => Expect::string(),
//			'verifier_cache' => '@todo',
//			'auth_token_cache' => '@todo',
//			'http_request_logger' => '@todo',
//			'http_request_debug_logger' => '@todo',
		]);
	}

	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();
		$config = (array) $this->getConfig();

		$builder->addDefinition($this->prefix('factory'))
			->setFactory(ExtensionFactory::class)
			->setArguments([$config])
			->setAutowired(false);

		$this->registerService('database', Database::class, $config, $builder);
		$this->registerService('auth', Auth::class, $config, $builder);
		$this->registerService('storage', Storage::class, $config, $builder);
		$this->registerService('remoteConfig', RemoteConfig::class, $config, $builder);
		$this->registerService('messaging', Messaging::class, $config, $builder);
		$this->registerService('firestore', Firestore::class, $config, $builder);
		$this->registerService('dynamicLinks', DynamicLinks::class, $config, $builder);
	}

	/**
	 * @param array<string,mixed> $config
	 */
	private function registerService(string $name, string $type, array $config, ContainerBuilder $builder): void
	{
		$builder->addDefinition($this->prefix($name))
			->setType($type)
			->setCreator('@' . $this->prefix('factory') . '::create' . ucfirst($name), [$config])
			// @phpstan-ignore-next-line
			->setAutowired($config['autowired']);
	}

}
