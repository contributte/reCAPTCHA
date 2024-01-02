<?php declare(strict_types = 1);

namespace Tests\Cases;

use Contributte\ReCaptcha\ReCaptchaProvider;
use Contributte\ReCaptcha\ReCaptchaResponse;
use Contributte\Tester\Toolkit;
use Nette\Forms\Controls\BaseControl;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

final class ControlMock extends BaseControl
{

	public function getValue(): string
	{
		return 'test';
	}

}

Toolkit::test(function (): void {
	$key = 'key';
	$validator = new ReCaptchaProvider($key, 'secret');

	$response = $validator->validate('test');
	Assert::type(ReCaptchaResponse::class, $response);

	Assert::false($response->isSuccess());
	Assert::notEqual(null, $response->getError());
});

Toolkit::test(function (): void {
	$key = 'key';
	$validator = new ReCaptchaProvider($key, 'secret');

	Assert::false($validator->validateControl(new ControlMock()));
});

Toolkit::test(function (): void {
	$key = 'key';
	$validator = new ReCaptchaProvider($key, 'secret');

	Assert::false($validator->validateControl(new ControlMock()));
});
