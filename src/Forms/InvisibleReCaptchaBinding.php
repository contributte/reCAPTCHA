<?php

namespace Contributte\ReCaptcha\Forms;

use Contributte\ReCaptcha\ReCaptchaProvider;
use Nette\Forms\Container;

/**
 * @author Milan Felix Sulc <sulcmil@gmail.com> | Jan Galek <jan.galek@troia-studio.cz>
 */
final class InvisibleReCaptchaBinding
{

	/**
	 * @param ReCaptchaProvider $provider
	 * @param string $name
	 * @return void
	 */
	public static function bind(ReCaptchaProvider $provider, $name = 'addInvisibleReCaptcha')
	{
		// Bind to form container

		Container::extensionMethod($name, function ($container, $name = 'recaptcha', $required = TRUE) use ($provider) {
			$field = new InvisibleReCaptchaField($provider);
			$field->setRequired($required);
			$container[$name] = $field;

			return $container[$name];
		});
	}

}
