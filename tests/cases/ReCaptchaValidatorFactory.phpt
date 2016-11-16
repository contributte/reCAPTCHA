<?php

namespace Tests;

/**
 * Test: ReCaptchaValidatorFactory
 */

use Minetro\Forms\reCAPTCHA\IReCaptchaValidatorFactory;
use Minetro\Forms\reCAPTCHA\ReCaptchaValidator;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

final class MockFactory implements IReCaptchaValidatorFactory
{

    /**
     * @return ReCaptchaValidator
     */
    public function create()
    {
        return new ReCaptchaValidator(NULL);
    }

}

test(function () {
    $factory = new MockFactory();
    $validator = $factory->create();

    Assert::type(ReCaptchaValidator::class, $validator);
});
