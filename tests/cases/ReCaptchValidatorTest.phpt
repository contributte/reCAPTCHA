<?php

namespace Tests;

/**
 * Test: ReCaptchaValidator
 */

use Minetro\Forms\reCAPTCHA\ReCaptchaResponse;
use Minetro\Forms\reCAPTCHA\ReCaptchaValidator;
use Nette\Forms\Controls\BaseControl;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

final class ControlMock extends BaseControl
{

    /**
     * @return string
     */
    public function getValue()
    {
        return 'test';
    }

}

final class ValidatorMock extends ReCaptchaValidator
{

    /**
     * @param mixed $response
     * @param string $remoteIp
     * @return mixed
     */
    public function makeRequest($response, $remoteIp = NULL)
    {
        parent::makeRequest($response, '127.0.0.0');

        return NULL;
    }

}

final class TrueValidatorMock extends ReCaptchaValidator
{

    /**
     * @param mixed $response
     * @param string $remoteIp
     * @return string
     */
    public function makeRequest($response, $remoteIp = NULL)
    {
        return json_encode(['success' => TRUE]);
    }

}

test(function () {
    $key = 'key';
    $validator = new ReCaptchaValidator($key);

    $response = $validator->validate('test');
    Assert::type(ReCaptchaResponse::class, $response);

    Assert::false($response->isSuccess());
    Assert::notEqual(NULL, $response->getError());
});

test(function () {
    $key = 'key';
    $validator = new ReCaptchaValidator($key);

    Assert::false($validator->validateControl(new ControlMock()));
});

test(function () {
    $key = 'key';
    $validator = new ValidatorMock($key);

    Assert::false($validator->validateControl(new ControlMock()));
});

test(function () {
    $key = 'key';
    $validator = new TrueValidatorMock($key);

    $response = $validator->validate('test');
    Assert::type(ReCaptchaResponse::class, $response);
    Assert::true($response->isSuccess());
});
