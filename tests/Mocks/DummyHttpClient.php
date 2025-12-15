<?php declare(strict_types = 1);

namespace Tests\Mocks;

use Contributte\ReCaptcha\Http\HttpClient;

final class DummyHttpClient implements HttpClient
{

	private ?string $response = null;

	/** @var string[] */
	private array $requestedUrls = [];

	public function get(string $url): string|null
	{
		$this->requestedUrls[] = $url;

		return $this->response;
	}

	public function setResponse(?string $response): void
	{
		$this->response = $response;
	}

	/**
	 * @return string[]
	 */
	public function getRequestedUrls(): array
	{
		return $this->requestedUrls;
	}

}
