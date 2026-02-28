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
use Tests\Mocks\ReCaptchaProviderExposed;

require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/../Mocks/ReCaptchaProviderExposed.php';

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

Toolkit::test(function (): void {
	$provider = Mockery::mock(ReCaptchaProvider::class)
		->shouldAllowMockingProtectedMethods()
		->makePartial();

	$provider->shouldReceive('makeRequest')
		->once()
		->andReturn(Json::encode([
			'success' => true,
			'score' => 0.7,
			'action' => 'submit',
			'hostname' => 'example.com',
			'challenge_ts' => '2024-01-01T00:00:00Z',
		]));

	$provider->setMinimalScore(0.0);
	$response = $provider->validate('test');
	Assert::true($response->isSuccess());
	Assert::equal([
		'success' => true,
		'score' => 0.7,
		'action' => 'submit',
		'hostname' => 'example.com',
		'challenge_ts' => '2024-01-01T00:00:00Z',
	], $response->getData());
});

// makeRequest returns null when response is null
Toolkit::test(function (): void {
	$provider = new ReCaptchaProviderExposed('key', 'secret');
	Assert::null($provider->makeRequest(null));
});

// makeRequest returns null when response is empty string
Toolkit::test(function (): void {
	$provider = new ReCaptchaProviderExposed('key', 'secret');
	Assert::null($provider->makeRequest(''));
});

// makeRequest includes remoteip in the URL when provided
Toolkit::test(function (): void {
	$httpClient = new DummyHttpClient();
	$httpClient->setResponse(Json::encode(['success' => true]));

	$provider = new ReCaptchaProviderExposed('key', 'secret');
	$provider->setHttpClient($httpClient);
	$provider->makeRequest('token', '1.2.3.4');

	$url = $httpClient->getRequestedUrls()[0];
	Assert::contains('remoteip=1.2.3.4', $url);
});

// makeRequest omits remoteip when not provided
Toolkit::test(function (): void {
	$httpClient = new DummyHttpClient();
	$httpClient->setResponse(Json::encode(['success' => true]));

	$provider = new ReCaptchaProviderExposed('key', 'secret');
	$provider->setHttpClient($httpClient);
	$provider->makeRequest('token');

	$url = $httpClient->getRequestedUrls()[0];
	Assert::notContains('remoteip', $url);
});

// getData() is available even when validation fails due to minimal score threshold
Toolkit::test(function (): void {
	$provider = Mockery::mock(ReCaptchaProvider::class)
		->shouldAllowMockingProtectedMethods()
		->makePartial();

	$provider->shouldReceive('makeRequest')
		->once()
		->andReturn(Json::encode([
			'success' => true,
			'score' => 0.7,
		]));

	$provider->setMinimalScore(0.9);
	$response = $provider->validate('test');
	Assert::false($response->isSuccess());
	Assert::equal(0.7, $response->getData()['score']);
});
