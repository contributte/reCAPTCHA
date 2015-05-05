<?php

namespace Minetro\Forms\reCAPTCHA;

use Nette\Forms\Container;

/**
 * reCAPTCHA Binding
 *
 * @author Milan Felix Sulc <sulcmil@gmail.com>
 */
class ReCaptchaBinding
{

    /**
     * @param string $siteKey
     * @param string $name
     * @return void
     */
    public static function bind($siteKey = NULL, $name = 'addReCaptcha')
    {
        if ($siteKey == NULL) {
            // Recieve siteKey automatic
            $siteKey = ReCaptchaHolder::getSiteKey();
        }

        // Bind to form container
        Container::extensionMethod($name, function ($container, $name, $label = NULL) use ($siteKey) {
            return $container[$name] = new ReCaptchaField($siteKey);
        });
    }
}
