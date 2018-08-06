<?php

namespace Tests\Forms;

/**
 * Test: InvisibleReCaptchaBinding
 */

use Contributte\ReCaptcha\Forms\InvisibleReCaptchaBinding;
use Contributte\ReCaptcha\Forms\InvisibleReCaptchaField;
use Contributte\ReCaptcha\ReCaptchaProvider;
use Nette\Forms\Form;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

test(function () {
	$provider = new ReCaptchaProvider('foo', 'bar');
	InvisibleReCaptchaBinding::bind($provider);

	$form = new Form();
	$recaptcha = $form->addInvisibleReCaptcha('recaptcha');

	Assert::type(InvisibleReCaptchaField::class, $recaptcha);
	Assert::true($form->offsetExists('recaptcha'));
	Assert::true($recaptcha->isRequired());
	Assert::same('foo', $recaptcha->getControl()->{'data-sitekey'});
});

test(function () {
	$provider = new ReCaptchaProvider('foo', 'bar');
	InvisibleReCaptchaBinding::bind($provider);

	$form = new Form();
	$recaptcha = $form->addInvisibleReCaptcha('recaptcha', FALSE);
	Assert::false($recaptcha->isRequired());
});


test(function () {
	$provider = new ReCaptchaProvider('foo', 'bar');
	InvisibleReCaptchaBinding::bind($provider);

	$form = new Form();
	$recaptcha = $form->addInvisibleReCaptcha('recaptcha', FALSE, 'Are you bot-bot?');
	Assert::false($recaptcha->isRequired());
});
