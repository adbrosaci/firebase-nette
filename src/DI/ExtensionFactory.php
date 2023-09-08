<?php declare(strict_types = 1);

namespace Adbros\Firebase\DI;

use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Contract\DynamicLinks;
use Kreait\Firebase\Contract\Firestore;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Contract\RemoteConfig;
use Kreait\Firebase\Contract\Storage;
use Kreait\Firebase\Factory;

class ExtensionFactory
{

	private Factory $firebaseFactory;

	/**
	 * @param array<string,mixed> $config
	 */
	public function __construct(array $config)
	{
		$this->firebaseFactory = new Factory();

		if (isset($config['credentials'])) {
			// @phpstan-ignore-next-line
			$this->firebaseFactory = $this->firebaseFactory->withServiceAccount($config['credentials']);
		}

		if (isset($config['database_uri'])) {
			// @phpstan-ignore-next-line
			$this->firebaseFactory = $this->firebaseFactory->withDatabaseUri($config['database_uri']);
		}

		if (isset($config['tenant_id'])) {
			// @phpstan-ignore-next-line
			$this->firebaseFactory = $this->firebaseFactory->withTenantId($config['tenant_id']);
		}
	}

	/**
	 * @param array<string,mixed> $config
	 */
	public function createDatabase(array $config): Database
	{
		return $this->firebaseFactory->createDatabase();
	}

	/**
	 * @param array<string,mixed> $config
	 */
	public function createAuth(array $config): Auth
	{
		return $this->firebaseFactory->createAuth();
	}

	/**
	 * @param array<string,mixed> $config
	 */
	public function createStorage(array $config): Storage
	{
		return $this->firebaseFactory->createStorage();
	}

	/**
	 * @param array<string,mixed> $config
	 */
	public function createRemoteConfig(array $config): RemoteConfig
	{
		return $this->firebaseFactory->createRemoteConfig();
	}

	/**
	 * @param array<string,mixed> $config
	 */
	public function createMessaging(array $config): Messaging
	{
		return $this->firebaseFactory->createMessaging();
	}

	/**
	 * @param array<string,mixed> $config
	 */
	public function createFirestore(array $config): Firestore
	{
		return $this->firebaseFactory->createFirestore();
	}

	/**
	 * @param array<string,mixed> $config
	 */
	public function createDynamicLinks(array $config): DynamicLinks
	{
		$defaultDynamicLinksDomain = $config['default_dynamic_links_domain'] ?? null;

		// @phpstan-ignore-next-line
		return $this->firebaseFactory->createDynamicLinksService($defaultDynamicLinksDomain);
	}

}
