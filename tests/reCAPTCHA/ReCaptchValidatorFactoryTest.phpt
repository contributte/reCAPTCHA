<?php

/**
 * Test: ReCaptchaValidatorFactory
 */

use Minetro\Forms\reCAPTCHA\IReCaptchaValidatorFactory;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

final class FactoryMock implements IReCaptchaValidatorFactory
{

    function create($secretKey)
    {
        return $secretKey;
    }

}

test(function () {
    $mock = new FactoryMock();
    $key = 'key';
    Assert::equal($key, $mock->create($key));
});
