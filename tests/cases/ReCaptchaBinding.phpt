<?php

namespace Tests;

/**
 * Test: ReCaptchaBinding
 */

use Minetro\Forms\reCAPTCHA\ReCaptchaBinding;
use Minetro\Forms\reCAPTCHA\ReCaptchaField;
use Nette\Forms\Form;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

test(function () {
    $siteKey = 'key';
    ReCaptchaBinding::bind($siteKey);

    $form = new Form();
    $recaptcha = $form->addReCaptcha('recaptcha');

    Assert::type(ReCaptchaField::class, $recaptcha);
    Assert::true($form->offsetExists('recaptcha'));
    Assert::same($siteKey, $recaptcha->getSiteKey());
});

test(function () {
    $siteKey = 'key';
    ReCaptchaBinding::bind($siteKey);

    $form = new Form();
    $inputName = 'test';
    $recaptcha = $form->addReCaptcha($inputName);

    Assert::type(ReCaptchaField::class, $recaptcha);
    Assert::true($form->offsetExists($inputName));
    Assert::same($siteKey, $recaptcha->getSiteKey());
});
