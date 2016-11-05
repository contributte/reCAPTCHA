<?php

namespace Minetro\Forms\reCAPTCHA;

use Nette\Forms\Container;

/**
 * reCAPTCHA Binding
 *
 * @author Milan Felix Sulc <sulcmil@gmail.com>
 */
final class ReCaptchaBinding
{

    /**
     * @param string $siteKey
     * @param string $name
     * @return void
     */
    public static function bind($siteKey, $name = 'addReCaptcha')
    {
        // Bind to form container
        Container::extensionMethod($name, function ($container, $name = 'recaptcha', $label = NULL) use ($siteKey) {
            return $container[$name] = new ReCaptchaField($siteKey);
        });
    }
}
