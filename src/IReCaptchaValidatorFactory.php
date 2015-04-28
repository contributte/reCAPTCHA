<?php

namespace Minetro\Forms\reCAPTCHA;

/**
 * reCAPTCHA Validator Factory Interface
 *
 * @author Milan Felix Sulc <sulcmil@gmail.com>
 */
interface IReCaptchaValidatorFactory
{

    /**
     * @return ReCaptchaValidator
     */
    function create();
}
