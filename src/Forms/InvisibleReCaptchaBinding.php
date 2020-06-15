<?php declare(strict_types = 1);

namespace Contributte\ReCaptcha\Forms;

use Contributte\ReCaptcha\ReCaptchaProvider;
use Nette\Forms\Container;

final class InvisibleReCaptchaBinding
{

	public static function bind(ReCaptchaProvider $provider, string $name = 'addInvisibleReCaptcha'): void
	{
		// Bind to form container
		Container::extensionMethod($name, function (Container $container, string $name = 'recaptcha', bool $required = true, ?string $message = null) use ($provider): InvisibleReCaptchaField {
			$field = new InvisibleReCaptchaField($provider, $message);
			$field->setRequired($required);
			$container[$name] = $field;

			return $field;
		});
	}

}
