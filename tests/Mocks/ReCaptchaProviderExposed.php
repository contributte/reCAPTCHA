<?php declare(strict_types = 1);

namespace Tests\Mocks;

use Contributte\ReCaptcha\ReCaptchaProvider;

final class ReCaptchaProviderExposed extends ReCaptchaProvider
{

	public function makeRequest(?string $response, ?string $remoteIp = null): string|null
	{
		return parent::makeRequest($response, $remoteIp);
	}

}
