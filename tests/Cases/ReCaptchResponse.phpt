<?php declare(strict_types = 1);

namespace Tests\Cases;

use Contributte\ReCaptcha\ReCaptchaResponse;
use Contributte\Tester\Toolkit;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

Toolkit::test(function (): void {
	$response = new ReCaptchaResponse(true);
	Assert::true($response->isSuccess());
});

Toolkit::test(function (): void {
	$response = new ReCaptchaResponse(true);
	Assert::equal('1', (string) $response);
});

Toolkit::test(function (): void {
	$error = 'Some error';
	$response = new ReCaptchaResponse(false, $error);
	Assert::false($response->isSuccess());
	Assert::equal($error, $response->getError());
});
