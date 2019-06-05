<?php declare(strict_types = 1);

namespace Contributte\ReCaptcha\Forms;

use Contributte\ReCaptcha\ReCaptchaProvider;
use Nette\Forms\Container;

final class ReCaptchaBinding
{

	public static function bind(ReCaptchaProvider $provider, string $name = 'addReCaptcha'): void
	{
		// Bind to form container
		Container::extensionMethod($name, function (Container $container, string $name = 'recaptcha', string $label = 'ReCaptcha', bool $required = true, ?string $message = null) use ($provider): ReCaptchaField {
			$field = new ReCaptchaField($provider, $label, $message);
			$field->setRequired($required);
			$container[$name] = $field;

			return $field;
		});
	}

}
