<?php

namespace Minetro\Forms\reCAPTCHA;

/**
 * @author Milan Felix Sulc <sulcmil@gmail.com>
 */
interface IReCaptchaValidatorFactory
{

    /**
     * @return ReCaptchaValidator
     */
    public function create();

}
