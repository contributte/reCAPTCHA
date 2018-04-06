<?php

namespace Tests;

/**
 * Test: ReCaptchaProvider
 */

use Contributte\ReCaptcha\ReCaptchaProvider;
use Contributte\ReCaptcha\ReCaptchaResponse;
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

test(function () {
	$key = 'key';
	$validator = new ReCaptchaProvider($key, NULL);

	$response = $validator->validate('test');
	Assert::type(ReCaptchaResponse::class, $response);

	Assert::false($response->isSuccess());
	Assert::notEqual(NULL, $response->getError());
});

test(function () {
	$key = 'key';
	$validator = new ReCaptchaProvider($key, NULL);

	Assert::false($validator->validateControl(new ControlMock()));
});

test(function () {
	$key = 'key';
	$validator = new ReCaptchaProvider($key, NULL);

	Assert::false($validator->validateControl(new ControlMock()));
});
