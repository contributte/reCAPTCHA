<?php

namespace Tests\Forms;

/**
 * Test: ReCaptchaBinding
 */

use Minetro\ReCaptcha\Forms\ReCaptchaBinding;
use Minetro\ReCaptcha\Forms\ReCaptchaField;
use Minetro\ReCaptcha\ReCaptchaProvider;
use Nette\Forms\Form;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

test(function () {
    $provider = new ReCaptchaProvider('foo', 'bar');
    ReCaptchaBinding::bind($provider);

    $form = new Form();
    $recaptcha = $form->addReCaptcha('recaptcha');

    Assert::type(ReCaptchaField::class, $recaptcha);
    Assert::true($form->offsetExists('recaptcha'));
    Assert::same('foo', $recaptcha->getControl()->{'data-sitekey'});
});
