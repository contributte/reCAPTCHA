<?php declare(strict_types = 1);

namespace Tests\Cases\Http;

use Contributte\ReCaptcha\Http\StreamHttpClient;
use Contributte\ReCaptcha\ReCaptchaProvider;
use Contributte\Tester\Toolkit;
use Nette\Utils\Json;
use Tester\Assert;
use Tests\Mocks\DummyHttpClient;

require __DIR__ . '/../../bootstrap.php';

// Test custom HttpClient injection via setter
Toolkit::test(function (): void {
	$httpClient = new DummyHttpClient();
	$httpClient->setResponse(Json::encode([
		'success' => true,
	]));

	$provider = new ReCaptchaProvider('site-key', 'secret-key');
	$provider->setHttpClient($httpClient);

	$response = $provider->validate('test-token');

	Assert::true($response->isSuccess());
	Assert::count(1, $httpClient->getRequestedUrls());
	Assert::contains('secret=secret-key', $httpClient->getRequestedUrls()[0]);
	Assert::contains('response=test-token', $httpClient->getRequestedUrls()[0]);
});

// Test custom HttpClient with failed response
Toolkit::test(function (): void {
	$httpClient = new DummyHttpClient();
	$httpClient->setResponse(Json::encode([
		'success' => false,
		'error-codes' => ['invalid-input-response'],
	]));

	$provider = new ReCaptchaProvider('site-key', 'secret-key');
	$provider->setHttpClient($httpClient);

	$response = $provider->validate('invalid-token');

	Assert::false($response->isSuccess());
	Assert::same(['invalid-input-response'], $response->getError());
});

// Test custom HttpClient with null response
Toolkit::test(function (): void {
	$httpClient = new DummyHttpClient();
	$httpClient->setResponse(null);

	$provider = new ReCaptchaProvider('site-key', 'secret-key');
	$provider->setHttpClient($httpClient);

	$response = @$provider->validate('test-token'); // Suppress E_USER_WARNING

	Assert::null($response);
});

// Test custom HttpClient with score validation
Toolkit::test(function (): void {
	$httpClient = new DummyHttpClient();
	$httpClient->setResponse(Json::encode([
		'success' => true,
		'score' => 0.9,
	]));

	$provider = new ReCaptchaProvider('site-key', 'secret-key', 0.5);
	$provider->setHttpClient($httpClient);

	$response = $provider->validate('test-token');

	Assert::true($response->isSuccess());
});

// Test custom HttpClient with low score
Toolkit::test(function (): void {
	$httpClient = new DummyHttpClient();
	$httpClient->setResponse(Json::encode([
		'success' => true,
		'score' => 0.2,
	]));

	$provider = new ReCaptchaProvider('site-key', 'secret-key', 0.5);
	$provider->setHttpClient($httpClient);

	$response = $provider->validate('test-token');

	Assert::false($response->isSuccess());
});

// Test getHttpClient returns same instance
Toolkit::test(function (): void {
	$httpClient = new DummyHttpClient();

	$provider = new ReCaptchaProvider('site-key', 'secret-key');
	$provider->setHttpClient($httpClient);

	Assert::same($httpClient, $provider->getHttpClient());
});

// Test getHttpClient creates default StreamHttpClient
Toolkit::test(function (): void {
	$provider = new ReCaptchaProvider('site-key', 'secret-key');

	$client = $provider->getHttpClient();

	Assert::type(StreamHttpClient::class, $client);
	// Should return the same instance on subsequent calls
	Assert::same($client, $provider->getHttpClient());
});
