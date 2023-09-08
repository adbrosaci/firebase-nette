<?php declare(strict_types = 1);

namespace Adbros\Firebase\DI;

use Kreait\Firebase\Factory;

/**
 * @phpstan-type ServiceAccountShape array{
 *     project_id: non-empty-string,
 *     client_email: non-empty-string,
 *     private_key: non-empty-string,
 *     type: 'service_account'
 * }
 */
class ExtensionFactory
{

	/**
	 * @param array{credentials:non-empty-string|ServiceAccountShape} $config
	 */
	public static function create(array $config): Factory
	{
		$factory = new Factory();

		return $factory->withServiceAccount($config['credentials']);
	}

}
