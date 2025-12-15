<?php declare(strict_types = 1);

namespace Contributte\ReCaptcha;

use Contributte\ReCaptcha\Http\HttpClient;
use Contributte\ReCaptcha\Http\StreamHttpClient;
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

	private ?HttpClient $httpClient = null;

	public function __construct(
		private readonly string $siteKey,
		private readonly string $secretKey,
		private float $minimalScore = 0, // Range 0..1 (1.0 is very likely a good interaction, 0.0 is very likely a bot)
		private readonly int $timeout = 5,
		private readonly int $retries = 3,
		private readonly ?string $proxy = null,
	)
	{
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
		return ($answer['success'] === true && ($this->minimalScore <= 0
				|| !isset($answer['score']) || $answer['score'] >= $this->minimalScore))
			? new ReCaptchaResponse(true)
			: new ReCaptchaResponse(false, $answer['error-codes'] ?? null);
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

	public function setMinimalScore(float $score): void
	{
		$this->minimalScore = $score;
	}

	public function setHttpClient(HttpClient $httpClient): void
	{
		$this->httpClient = $httpClient;
	}

	public function getHttpClient(): HttpClient
	{
		if ($this->httpClient === null) {
			$this->httpClient = new StreamHttpClient($this->timeout, $this->retries, $this->proxy);
		}

		return $this->httpClient;
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

		$content = $this->getHttpClient()->get($this->buildUrl($params));

		if ($content === null) {
			trigger_error(self::class . ': Unable to connect to Google ReCaptcha API.', E_USER_WARNING);
		}

		return $content;
	}

	/**
	 * @param mixed[] $parameters
	 */
	protected function buildUrl(array $parameters = []): string
	{
		return self::VERIFICATION_URL . '?' . http_build_query($parameters);
	}

}
