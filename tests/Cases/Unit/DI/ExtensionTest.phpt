<?php declare(strict_types = 1);

namespace Tests\Cases\Unit\DI;

use Adbros\Firebase\DI\Extension;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Factory;
use Nette\DI\Compiler;
use Nette\DI\Container;
use Nette\DI\ContainerLoader;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../../../bootstrap.php';

/**
 * @testCase
 */
class ExtensionTest extends TestCase
{

	/**
	 * @dataProvider getConfigs
	 * @param array<string,mixed> $config
	 */
	public function testRegister(array $config): void
	{
		$loader = new ContainerLoader(constant('TEMP_DIR'), true);
		$class = $loader->load(function (Compiler $compiler) use ($config): void {
			$compiler->addExtension('firebase', new Extension())
				->addConfig([
					'firebase' => $config,
				]);
		}, 1);

		/** @var Container $container */
		$container = new $class();

		Assert::noError(function () use ($container): void {
			$container->getByType(Factory::class);
			$container->getByType(Messaging::class);
		});
	}

	/**
	 * @return array<array<string,mixed>>
	 */
	protected function getConfigs(): array
	{
		$credentials = __DIR__ . '/../../../fixtures/valid_credentials.json';

		Assert::notSame(false, $contents = file_get_contents($credentials));

		return [
			[
				'config' => [
					'credentials' => $credentials,
				],
			],
			[
				'config' => [
					'credentials' => $contents,
				],
			],
			[
				'config' => [
					'credentials' => json_decode($contents, true),
				],
			],
		];
	}

}

(new ExtensionTest())->run();
