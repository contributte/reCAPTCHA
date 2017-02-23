<?php

namespace Minetro\ReCaptcha\Forms;

use Minetro\ReCaptcha\ReCaptchaProvider;
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
        Container::extensionMethod($name, function ($container, $name = 'recaptcha', $label = NULL) use ($provider) {
            return $container[$name] = new ReCaptchaField($provider, $label);
        });
    }

}
