<?php declare(strict_types = 1);

namespace Contributte\ReCaptcha\DI;

use Contributte\ReCaptcha\Forms\InvisibleReCaptchaBinding;
use Contributte\ReCaptcha\Forms\ReCaptchaBinding;
use Contributte\ReCaptcha\ReCaptchaProvider;
use Nette\DI\CompilerExtension;
use Nette\PhpGenerator\ClassType;
use Nette\Schema\Expect;
use Nette\Schema\Schema;

final class ReCaptchaExtension extends CompilerExtension
{

	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'siteKey' => Expect::string()->required()->dynamic(),
			'secretKey' => Expect::string()->required()->dynamic(),
			'minimalScore' => Expect::anyOf(Expect::float()->min(0)->max(1), Expect::int()->min(0)->max(1))->default(0),
		]);
	}

	/**
	 * Register services
	 */
	public function loadConfiguration(): void
	{
		$config = (array) $this->getConfig();
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('provider'))
			->setFactory(ReCaptchaProvider::class, [$config['siteKey'], $config['secretKey'], $config['minimalScore']]);
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
