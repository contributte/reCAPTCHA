<?php declare(strict_types = 1);

namespace Contributte\ReCaptcha;

use Nette\Forms\Controls\BaseControl;
use Nette\SmartObject;

/**
 * @method onValidateControl(ReCaptchaProvider $provider, BaseControl $control)
 * @method onValidate(ReCaptchaProvider $provider, mixed $response)
 */
class ReCaptchaProvider
{

	use SmartObject;

	// ReCaptcha FTW!
	public const FORM_PARAMETER = 'g-recaptcha-response';
	public const VERIFICATION_URL = 'https://www.google.com/recaptcha/api/siteverify';

	/** @var callable[] */
	public array $onValidate = [];

	/** @var callable[] */
	public array $onValidateControl = [];

	private string $siteKey;

	private string $secretKey;

	public function __construct(string $siteKey, string $secretKey)
	{
		$this->siteKey = $siteKey;
		$this->secretKey = $secretKey;
	}

	public function getSiteKey(): string
	{
		return $this->siteKey;
	}

	public function validate(string $response): ?ReCaptchaResponse
	{
		// Fire events!
		$this->onValidate($this, $response);

		// Load response
		$response = $this->makeRequest($response);

		// Response is empty or failed
		if ($response === null || $response === '') {
			return null;
		}

		// Decode server answer (with key assoc reserved)
		/** @var mixed[] $answer */
		$answer = json_decode($response, true);

		// Return response
		return $answer['success'] === true ? new ReCaptchaResponse(true) : new ReCaptchaResponse(false, $answer['error-codes'] ?? null);
	}

	public function validateControl(BaseControl $control): bool
	{
		// Fire events!
		$this->onValidateControl($this, $control);

		// Get response
		/** @var scalar $value */
		$value = $control->getValue();
		$response = $this->validate(strval($value));

		if ($response !== null) {
			return $response->isSuccess();
		}

		return false;
	}

	protected function makeRequest(?string $response, ?string $remoteIp = null): string|null
	{
		if ($response === null || $response === '') {
			return null;
		}

		$params = [
			'secret' => $this->secretKey,
			'response' => $response,
		];

		if ($remoteIp !== null) {
			$params['remoteip'] = $remoteIp;
		}

		$content = file_get_contents($this->buildUrl($params));

		return $content === false ? null : $content;
	}

	/**
	 * @param mixed[] $parameters
	 */
	protected function buildUrl(array $parameters = []): string
	{
		return self::VERIFICATION_URL . '?' . http_build_query($parameters);
	}

}
