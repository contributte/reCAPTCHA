<?php

namespace Contributte\ReCaptcha;

use Nette\Forms\Controls\BaseControl;
use Nette\SmartObject;

/**
 * @author Milan Felix Sulc <sulcmil@gmail.com>
 *
 * @method onValidateControl(ReCaptchaProvider $provider, BaseControl $control)
 * @method onValidate(ReCaptchaProvider $provider, mixed $response)
 */
class ReCaptchaProvider
{

	use SmartObject;

	// ReCaptcha FTW!
	const FORM_PARAMETER = 'g-recaptcha-response';
	const VERIFICATION_URL = 'https://www.google.com/recaptcha/api/siteverify';

	/** @var array */
	public $onValidate = [];

	/** @var array */
	public $onValidateControl = [];

	/** @var string */
	private $siteKey;

	/** @var string */
	private $secretKey;

	/**
	 * @param string $siteKey
	 * @param string $secretKey
	 */
	public function __construct($siteKey, $secretKey)
	{
		$this->siteKey = $siteKey;
		$this->secretKey = $secretKey;
	}

	/**
	 * @return string
	 */
	public function getSiteKey()
	{
		return $this->siteKey;
	}

	/**
	 * VALIDATION **************************************************************
	 */

	/**
	 * @param mixed $response
	 * @return ReCaptchaResponse|FALSE
	 */
	public function validate($response)
	{
		// Fire events!
		$this->onValidate($this, $response);

		// Load response
		$response = $this->makeRequest($response);

		// Response is empty or failed..
		if (empty($response)) return FALSE;

		// Decode server answer (with key assoc reserved)
		$answer = json_decode($response, TRUE);

		// Return response
		if (trim($answer['success']) == TRUE) {
			return new ReCaptchaResponse(TRUE);
		} else {
			return new ReCaptchaResponse(FALSE, isset($answer['error-codes']) ? $answer['error-codes'] : NULL);
		}
	}

	/**
	 * @param BaseControl $control
	 * @return bool
	 */
	public function validateControl(BaseControl $control)
	{
		// Fire events!
		$this->onValidateControl($this, $control);

		// Get response
		$response = $this->validate($control->getValue());

		if ($response) {
			return $response->isSuccess();
		}

		return FALSE;
	}


	/**
	 * HELPERS *****************************************************************
	 */

	/**
	 * @param mixed $response
	 * @param string $remoteIp
	 * @return mixed
	 */
	protected function makeRequest($response, $remoteIp = NULL)
	{
		if (empty($response)) return NULL;

		$params = [
			'secret' => $this->secretKey,
			'response' => $response,
		];

		if ($remoteIp) {
			$params['remoteip'] = $remoteIp;
		}

		return @file_get_contents($this->buildUrl($params));
	}

	/**
	 * @param array $parameters
	 * @return string
	 */
	protected function buildUrl(array $parameters = [])
	{
		return self::VERIFICATION_URL . '?' . http_build_query($parameters);
	}

}
