<?php

namespace Tests;

/**
 * Test: ReCaptchaHolder
 */

use Minetro\Forms\reCAPTCHA\ReCaptchaHolder;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

test(function () {
    $key = 1;
    $holder = ReCaptchaHolder::factory($key);
    Assert::equal($key, $holder::getSiteKey());
});

test(function () {
    $key = NULL;
    $holder = ReCaptchaHolder::factory($key);
    Assert::null($holder::getSiteKey());
});
