<?php declare(strict_types = 1);

namespace Contributte\ReCaptcha\Http;

interface HttpClient
{

	public function get(string $url): string|null;

}
