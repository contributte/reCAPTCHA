<?php declare(strict_types = 1);

namespace Tests\Cases\Forms;

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
	$recaptcha = $form->addInvisibleReCaptcha('recaptcha', false);
	Assert::false($recaptcha->isRequired());
});


test(function () {
	$provider = new ReCaptchaProvider('foo', 'bar');
	InvisibleReCaptchaBinding::bind($provider);

	$form = new Form();
	$recaptcha = $form->addInvisibleReCaptcha('recaptcha', false, 'Are you bot-bot?');
	Assert::false($recaptcha->isRequired());
});
