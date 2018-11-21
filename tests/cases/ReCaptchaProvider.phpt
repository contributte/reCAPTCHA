<?php declare(strict_types = 1);

namespace Tests\Cases;

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
	$validator = new ReCaptchaProvider($key, 'secret');

	$response = $validator->validate('test');
	Assert::type(ReCaptchaResponse::class, $response);

	Assert::false($response->isSuccess());
	Assert::notEqual(null, $response->getError());
});

test(function () {
	$key = 'key';
	$validator = new ReCaptchaProvider($key, 'secret');

	Assert::false($validator->validateControl(new ControlMock()));
});

test(function () {
	$key = 'key';
	$validator = new ReCaptchaProvider($key, 'secret');

	Assert::false($validator->validateControl(new ControlMock()));
});
