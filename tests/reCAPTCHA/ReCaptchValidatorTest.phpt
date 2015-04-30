<?php

/**
 * Test: ReCaptchaValidator
 */

use Minetro\Forms\reCAPTCHA\ReCaptchaValidator;
use Nette\Forms\Controls\HiddenField;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

final class HiddenMock extends HiddenField
{
    public function getValue()
    {
        return 'test';
    }

}

test(function () {
    $key = 'key';
    $validator = new ReCaptchaValidator($key);

    $response = $validator->validate('test');
    Assert::type('Minetro\Forms\reCAPTCHA\ReCaptchaResponse', $response);

    Assert::false($response->isSuccess());
    Assert::notEqual(NULL, $response->getError());
});

test(function () {
    $key = 'key';
    $validator = new ReCaptchaValidator($key);

    Assert::false($validator->validateControl(new HiddenMock()));
});
