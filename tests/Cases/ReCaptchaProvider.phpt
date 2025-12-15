<?php declare(strict_types = 1);

namespace Tests\Cases;

use Contributte\ReCaptcha\ReCaptchaProvider;
use Contributte\ReCaptcha\ReCaptchaResponse;
use Contributte\Tester\Toolkit;
use Mockery;
use Nette\Forms\Controls\BaseControl;
use Nette\Utils\Json;
use Tester\Assert;
use Tests\Mocks\DummyHttpClient;

require __DIR__ . '/../bootstrap.php';

final class ControlMock extends BaseControl
{

	public function getValue(): string
	{
		return 'test';
	}

}

Toolkit::test(function (): void {
	$httpClient = new DummyHttpClient();
	$httpClient->setResponse(Json::encode([
		'success' => false,
		'error-codes' => ['invalid-input-response'],
	]));

	$provider = new ReCaptchaProvider('key', 'secret');
	$provider->setHttpClient($httpClient);

	$response = $provider->validate('test');
	Assert::type(ReCaptchaResponse::class, $response);

	Assert::false($response->isSuccess());
	Assert::notEqual(null, $response->getError());
});

Toolkit::test(function (): void {
	$httpClient = new DummyHttpClient();
	$httpClient->setResponse(Json::encode([
		'success' => false,
		'error-codes' => ['invalid-input-response'],
	]));

	$provider = new ReCaptchaProvider('key', 'secret');
	$provider->setHttpClient($httpClient);

	Assert::false($provider->validateControl(new ControlMock()));
});

// makeRequest returns null
Toolkit::test(function (): void {
	$provider = Mockery::mock(ReCaptchaProvider::class)
		->shouldAllowMockingProtectedMethods()
		->makePartial();

	$provider->shouldReceive('makeRequest')
		->once()
		->andReturn(null);

	Assert::null($provider->validate('test'));
});

// makeRequest returns success false
Toolkit::test(function (): void {
	$provider = Mockery::mock(ReCaptchaProvider::class)
		->shouldAllowMockingProtectedMethods()
		->makePartial();

	$provider->shouldReceive('makeRequest')
		->once()
		->andReturn(Json::encode([
			'success' => false,
			'error-codes' => ['test'],
		]));

	Assert::false($provider->validate('test')->isSuccess());
});

// scoring
Toolkit::test(function (): void {
	$provider = Mockery::mock(ReCaptchaProvider::class)
		->shouldAllowMockingProtectedMethods()
		->makePartial();

	$provider->shouldReceive('makeRequest')
		->once()
		->andReturn(Json::encode([
			'success' => true,
			'score' => 0.2,
		]));

	$provider->setMinimalScore(0.5);
	Assert::false($provider->validate('test')->isSuccess());

	$provider->setMinimalScore(0.1);
	Assert::true($provider->validate('test')->isSuccess());
});

// score is missing
Toolkit::test(function (): void {
	$provider = Mockery::mock(ReCaptchaProvider::class)
		->shouldAllowMockingProtectedMethods()
		->makePartial();

	$provider->shouldReceive('makeRequest')
		->once()
		->andReturn(Json::encode([
			'success' => true,
		]));

	$provider->setMinimalScore(0.5);
	Assert::true($provider->validate('test')->isSuccess());
});
