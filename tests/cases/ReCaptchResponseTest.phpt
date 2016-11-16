<?php

namespace Tests;

/**
 * Test: ReCaptchaResponse
 */

use Minetro\Forms\reCAPTCHA\ReCaptchaResponse;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

test(function () {
    $response = new ReCaptchaResponse(TRUE);
    Assert::true($response->isSuccess());
});

test(function () {
    $response = new ReCaptchaResponse(TRUE);
    Assert::equal('1', (string) $response);
});

test(function () {
    $error = 'Some error';
    $response = new ReCaptchaResponse(FALSE, $error);
    Assert::false($response->isSuccess());
    Assert::equal($error, $response->getError());
});
