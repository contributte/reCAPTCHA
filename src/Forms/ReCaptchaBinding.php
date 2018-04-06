<?php

namespace Contributte\ReCaptcha\Forms;

use Contributte\ReCaptcha\ReCaptchaProvider;
use Nette\Forms\Container;

/**
 * @author Milan Felix Sulc <sulcmil@gmail.com>
 */
final class ReCaptchaBinding
{

	/**
	 * @param ReCaptchaProvider $provider
	 * @param string $name
	 * @return void
	 */
	public static function bind(ReCaptchaProvider $provider, $name = 'addReCaptcha')
	{
		// Bind to form container
		Container::extensionMethod($name, function ($container, $name = 'recaptcha', $label = 'ReCaptcha', $required = TRUE, $message = NULL) use ($provider) {
			$field = new ReCaptchaField($provider, $label, $message);
			$field->setRequired($required);
			$container[$name] = $field;

			return $container[$name];
		});
	}

}
