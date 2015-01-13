<?php

namespace Forms\reCAPTCHA;

/**
 * reCAPTCHA Validator Factory Interface
 *
 * @author Milan Felix Sulc <sulcmil@gmail.com>
 */
interface IReCaptchaValidatorFactory
{

    /**
     * @param string $secretKey
     * @return ReCaptchaValidator
     */
    function create($secretKey);
}