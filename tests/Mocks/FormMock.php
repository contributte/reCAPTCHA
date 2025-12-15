<?php declare(strict_types = 1);

namespace Tests\Mocks;

use Contributte\ReCaptcha\ReCaptchaProvider;
use Nette\Forms\Form;
use Nette\Http\FileUpload;

final class FormMock extends Form
{

	private string $recaptchaResponse;

	public function __construct(string $recaptchaResponse = '')
	{
		parent::__construct('form');

		$this->recaptchaResponse = $recaptchaResponse;
	}

	public function getHttpData(?int $type = null, ?string $htmlName = null): FileUpload|array|string|null
	{
		if ($htmlName === ReCaptchaProvider::FORM_PARAMETER) {
			return $this->recaptchaResponse;
		}

		return $htmlName;
	}

	public function isAnchored(): bool
	{
		return true;
	}

}
