<?php

namespace Contributte\ReCaptcha\DI;

use Contributte\ReCaptcha\Forms\InvisibleReCaptchaBinding;
use Contributte\ReCaptcha\Forms\ReCaptchaBinding;
use Contributte\ReCaptcha\ReCaptchaProvider;
use Nette\DI\CompilerExtension;
use Nette\PhpGenerator\ClassType;
use Nette\Utils\Validators;

/**
 * @author Milan Felix Sulc <sulcmil@gmail.com>
 */
final class ReCaptchaExtension extends CompilerExtension
{

	/** @var array */
	private $defaults = [
		'siteKey' => NULL,
		'secretKey' => NULL,
	];

	/**
	 * Register services
	 *
	 * @return void
	 */
	public function loadConfiguration()
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
	 *
	 * @param ClassType $class
	 * @return void
	 */
	public function afterCompile(ClassType $class)
	{
		$method = $class->getMethod('initialize');
		$method->addBody(sprintf('%s::bind($this->getService(?));', ReCaptchaBinding::class), [$this->prefix('provider')]);
		$method->addBody(sprintf('%s::bind($this->getService(?));', InvisibleReCaptchaBinding::class), [$this->prefix('provider')]);
	}

}
