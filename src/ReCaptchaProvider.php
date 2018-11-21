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
	public $onValidate = [];

	/** @var callable[] */
	public $onValidateControl = [];

	/** @var string */
	private $siteKey;

	/** @var string */
	private $secretKey;

	public function __construct(string $siteKey, string $secretKey)
	{
		$this->siteKey = $siteKey;
		$this->secretKey = $secretKey;
	}

	public function getSiteKey(): string
	{
		return $this->siteKey;
	}

	/**
	 * @param mixed $response
	 */
	public function validate($response): ?ReCaptchaResponse
	{
		// Fire events!
		$this->onValidate($this, $response);

		// Load response
		$response = $this->makeRequest($response);

		// Response is empty or failed..
		if (empty($response)) return null;

		// Decode server answer (with key assoc reserved)
		$answer = json_decode($response, true);

		// Return response
		if ($answer['success'] === true) {
			return new ReCaptchaResponse(true);
		} else {
			return new ReCaptchaResponse(false, $answer['error-codes'] ?? null);
		}
	}

	public function validateControl(BaseControl $control): bool
	{
		// Fire events!
		$this->onValidateControl($this, $control);

		// Get response
		$response = $this->validate($control->getValue());

		if ($response !== null) {
			return $response->isSuccess();
		}

		return false;
	}


	/**
	 * @param mixed $response
	 * @return mixed
	 */
	protected function makeRequest($response, ?string $remoteIp = null)
	{
		if (empty($response)) return null;

		$params = [
			'secret' => $this->secretKey,
			'response' => $response,
		];

		if ($remoteIp !== null) {
			$params['remoteip'] = $remoteIp;
		}

		return @file_get_contents($this->buildUrl($params));
	}

	/**
	 * @param mixed[] $parameters
	 */
	protected function buildUrl(array $parameters = []): string
	{
		return self::VERIFICATION_URL . '?' . http_build_query($parameters);
	}

}
