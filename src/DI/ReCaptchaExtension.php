<?php declare(strict_types = 1);

namespace Contributte\ReCaptcha\DI;

use Contributte\ReCaptcha\Forms\InvisibleReCaptchaBinding;
use Contributte\ReCaptcha\Forms\ReCaptchaBinding;
use Contributte\ReCaptcha\ReCaptchaProvider;
use Nette;
use Nette\DI\CompilerExtension;
use Nette\PhpGenerator\ClassType;

final class ReCaptchaExtension extends CompilerExtension
{

	/**
	 * Register services
	 */
	public function loadConfiguration(): void
	{
		$config = (array) $this->getConfig();
		$builder = $this->getContainerBuilder();

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

	public function getConfigSchema(): Nette\Schema\Schema
	{
		return Nette\Schema\Expect::structure([
			'siteKey' => Nette\Schema\Expect::string()->required(),
			'secretKey' => Nette\Schema\Expect::string()->required(),
		]);
	}

}
