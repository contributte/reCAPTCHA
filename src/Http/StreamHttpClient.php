<?php declare(strict_types = 1);

namespace Contributte\ReCaptcha\Http;

final class StreamHttpClient implements HttpClient
{

	public function __construct(
		private readonly int $timeout = 5,
		private readonly int $retries = 3,
		private readonly ?string $proxy = null,
	)
	{
	}

	public function get(string $url): string|null
	{
		$content = false;
		$retries = $this->retries;

		while ($retries > 0 && $content === false) {
			$content = @file_get_contents($url, false, $this->createContext());
			$retries--;
		}

		if ($content === false) {
			trigger_error(self::class . ': Unable to make HTTP request.', E_USER_WARNING);

			return null;
		}

		return $content;
	}

	/**
	 * @return resource
	 */
	private function createContext()
	{
		$options = [
			'http' => [
				'timeout' => $this->timeout,
			],
		];

		if ($this->proxy !== null) {
			$proxyUrl = $this->normalizeProxyUrl($this->proxy);
			$options['http']['proxy'] = $proxyUrl;
			$options['http']['request_fulluri'] = true;

			// Handle proxy authentication if present in URL
			$parsedProxy = parse_url($this->proxy);
			if ($parsedProxy !== false && isset($parsedProxy['user'])) {
				$credentials = $parsedProxy['user'];
				if (isset($parsedProxy['pass'])) {
					$credentials .= ':' . $parsedProxy['pass'];
				}

				$options['http']['header'] = 'Proxy-Authorization: Basic ' . base64_encode($credentials);
			}
		}

		return stream_context_create($options);
	}

	private function normalizeProxyUrl(string $proxy): string
	{
		$parsed = parse_url($proxy);

		// If already in tcp:// format, return as-is
		if ($parsed !== false && isset($parsed['scheme']) && $parsed['scheme'] === 'tcp') {
			// Remove auth from tcp:// URL for the proxy option
			$host = $parsed['host'] ?? '';
			$port = isset($parsed['port']) ? ':' . $parsed['port'] : '';

			return 'tcp://' . $host . $port;
		}

		// Convert http:// or https:// proxy to tcp:// format
		if ($parsed !== false && isset($parsed['host'])) {
			$host = $parsed['host'];
			$port = isset($parsed['port']) ? ':' . $parsed['port'] : ':8080';

			return 'tcp://' . $host . $port;
		}

		// Handle simple host:port format
		if (strpos($proxy, '://') === false) {
			// Check if port is already included
			if (strpos($proxy, ':') !== false) {
				return 'tcp://' . $proxy;
			}

			return 'tcp://' . $proxy . ':8080';
		}

		return $proxy;
	}

}
