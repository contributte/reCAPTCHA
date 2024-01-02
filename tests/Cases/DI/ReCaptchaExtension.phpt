<?php declare(strict_types = 1);

namespace Tests\Cases\DI;

use Contributte\ReCaptcha\DI\ReCaptchaExtension;
use Contributte\ReCaptcha\ReCaptchaProvider;
use Contributte\Tester\Environment;
use Contributte\Tester\Toolkit;
use Nette\DI\Compiler;
use Nette\DI\ContainerLoader;
use Nette\DI\InvalidConfigurationException;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

Toolkit::test(function (): void {
	$loader = new ContainerLoader(Environment::getTestDir());
	$class = $loader->load(function (Compiler $compiler): void {
		$compiler->addExtension('captcha', new ReCaptchaExtension());

		$compiler->addConfig([
			'captcha' => [
				'siteKey' => 'foobar',
				'secretKey' => 'foobar2',
			],
		]);
	}, 'SC' . time());

	$container = new $class();
	Assert::type(ReCaptchaProvider::class, $container->getByType(ReCaptchaProvider::class));
	Assert::equal('foobar', $container->getByType(ReCaptchaProvider::class)->getSiteKey());
});

Toolkit::test(function (): void {
	Assert::exception(function (): void {
		$loader = new ContainerLoader(Environment::getTestDir());
		$loader->load(function (Compiler $compiler): void {
			$compiler->addExtension('captcha', new ReCaptchaExtension());
			$compiler->addConfig([
				'captcha' => [
					'siteKey' => 'foobar',
				],
			]);
		}, 'SC2' . time());
	}, InvalidConfigurationException::class, 'The mandatory item \'captcha › secretKey\' is missing.');
});
