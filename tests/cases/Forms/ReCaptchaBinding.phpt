<?php declare(strict_types = 1);

namespace Tests\Cases\Forms;

/**
 * Test: ReCaptchaBinding
 */

use Contributte\ReCaptcha\Forms\ReCaptchaBinding;
use Contributte\ReCaptcha\Forms\ReCaptchaField;
use Contributte\ReCaptcha\ReCaptchaProvider;
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
	Assert::true($recaptcha->isRequired());
	Assert::same('ReCaptcha', $recaptcha->getLabel()->getText());
	Assert::same('foo', $recaptcha->getControl()->{'data-sitekey'});
});

test(function () {
	$provider = new ReCaptchaProvider('foo', 'bar');
	ReCaptchaBinding::bind($provider);

	$form = new Form();
	$recaptcha = $form->addReCaptcha('recaptcha', 'My label');
	Assert::same('My label', $recaptcha->getLabel()->getText());
});

test(function () {
	$provider = new ReCaptchaProvider('foo', 'bar');
	ReCaptchaBinding::bind($provider);

	$form = new Form();
	$recaptcha = $form->addReCaptcha('recaptcha', 'My label', false);
	Assert::false($recaptcha->isRequired());
});


test(function () {
	$provider = new ReCaptchaProvider('foo', 'bar');
	ReCaptchaBinding::bind($provider);

	$form = new Form();
	$recaptcha = $form->addReCaptcha('recaptcha', 'My label', false, 'Are you bot-bot?');
	Assert::false($recaptcha->isRequired());
	$rules = $recaptcha->getRules()->getIterator();
	$rule = end($rules);

	Assert::equal('Are you bot-bot?', $rule->message);
});
