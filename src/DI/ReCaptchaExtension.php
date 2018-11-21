<?php declare(strict_types = 1);

namespace Contributte\ReCaptcha\DI;

use Contributte\ReCaptcha\Forms\InvisibleReCaptchaBinding;
use Contributte\ReCaptcha\Forms\ReCaptchaBinding;
use Contributte\ReCaptcha\ReCaptchaProvider;
use Nette\DI\CompilerExtension;
use Nette\PhpGenerator\ClassType;
use Nette\Utils\Validators;

final class ReCaptchaExtension extends CompilerExtension
{

	/** @var mixed[] */
	private $defaults = [
		'siteKey' => null,
		'secretKey' => null,
	];

	/**
	 * Register services
	 */
	public function loadConfiguration(): void
	{
		$config = $this->validateConfig($this->defaults);
		$builder = $this->getContainerBuilder();

		Validators::assertField($config, 'siteKey', 'string');
		Validators::assertField($config, 'secretKey', 'string');

		$builder->addDefinition($this->prefix('provider'))
			->setFactory(ReCaptchaProvider::class, [$config['siteKey'], $config['secretKey']]);
	}

	/**
	 * Decorate initialize method
	 */
	public function afterCompile(ClassType $class): void
	{
		$method = $class->getMethod('initialize');
		$method->addBody(sprintf('%s::bind($this->getService(?));', ReCaptchaBinding::class), [$this->prefix('provider')]);
		$method->addBody(sprintf('%s::bind($this->getService(?));', InvisibleReCaptchaBinding::class), [$this->prefix('provider')]);
	}

}
