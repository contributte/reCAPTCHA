<?php declare(strict_types = 1);

namespace Tests\Cases\Forms;

use Contributte\ReCaptcha\Forms\InvisibleReCaptchaField;
use Contributte\ReCaptcha\Forms\ReCaptchaField;
use Contributte\ReCaptcha\ReCaptchaProvider;
use Contributte\Tester\Toolkit;
use Mockery;
use Nette\Utils\Json;
use Tester\Assert;
use Tests\Mocks\FormMock;

require __DIR__ . '/../../bootstrap.php';
require __DIR__ . '/../../Mocks/FormMock.php';

// InvisibleReCaptchaField errors
Toolkit::test(function (): void {
	$provider = Mockery::mock(ReCaptchaProvider::class, ['key', 'secret'])
		->shouldAllowMockingProtectedMethods()
		->makePartial();

	$provider->shouldReceive('makeRequest')
		->andReturn(Json::encode(['success' => false]));

	$form = new FormMock('test');
	$field = new InvisibleReCaptchaField($provider, 'Error message');
	$form->addComponent($field, 'recaptcha');

	$field->loadHttpData();
	$field->validate();

	Assert::true($field->hasErrors());
	Assert::contains('Error message', $field->getErrors());
});

// ReCaptchaField errors
Toolkit::test(function (): void {
	$provider = Mockery::mock(ReCaptchaProvider::class, ['key', 'secret'])
		->shouldAllowMockingProtectedMethods()
		->makePartial();

	$provider->shouldReceive('makeRequest')
		->andReturn(Json::encode(['success' => false]));

	$form = new FormMock('test');
	$field = new ReCaptchaField($provider, 'Captcha', 'Error message');
	$form->addComponent($field, 'recaptcha');

	$field->loadHttpData();
	$field->validate();

	Assert::true($field->hasErrors());
	Assert::contains('Error message', $field->getErrors());
});

// No errors on success
Toolkit::test(function (): void {
	$provider = Mockery::mock(ReCaptchaProvider::class, ['key', 'secret'])
		->shouldAllowMockingProtectedMethods()
		->makePartial();

	$provider->shouldReceive('makeRequest')
		->andReturn(Json::encode(['success' => true]));

	$form = new FormMock('test');
	$field = new InvisibleReCaptchaField($provider);
	$form->addComponent($field, 'recaptcha');

	$field->loadHttpData();
	$field->validate();

	Assert::false($field->hasErrors());
	Assert::equal([], $field->getErrors());
});

Mockery::close();
