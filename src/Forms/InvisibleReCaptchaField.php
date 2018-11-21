<?php declare(strict_types = 1);

namespace Contributte\ReCaptcha\Forms;

use Contributte\ReCaptcha\ReCaptchaProvider;
use Nette\Forms\Controls\HiddenField;
use Nette\Forms\Form;
use Nette\InvalidStateException;
use Nette\Utils\Html;

class InvisibleReCaptchaField extends HiddenField
{

	/** @var ReCaptchaProvider */
	private $provider;

	/** @var bool */
	private $configured = false;

	public function __construct(ReCaptchaProvider $provider, ?string $message = null)
	{
		parent::__construct();
		$this->provider = $provider;

		$this->setOmitted(true);
		$this->control = Html::el('div');
		$this->control->addClass('g-recaptcha');

		if ($message !== null) {
			$this->setMessage($message);
		}
	}

	public function loadHttpData(): void
	{
		parent::loadHttpData();
		$this->setValue($this->getForm()->getHttpData(Form::DATA_TEXT, ReCaptchaProvider::FORM_PARAMETER));
	}

	public function setMessage(string $message): self
	{
		if ($this->configured === true) {
			throw new InvalidStateException('Please call setMessage() only once or don\'t pass $message over addInvisibleReCaptcha()');
		}

		$this->addRule(function ($code) {
			return $this->verify() === true;
		}, $message);

		$this->configured = true;

		return $this;
	}

	public function verify(): bool
	{
		return $this->provider->validateControl($this) === true;
	}

	public function getControl(?string $caption = null): Html
	{
		$el = parent::getControl();
		$el->addAttributes([
			'data-sitekey' => $this->provider->getSiteKey(),
			'data-size' => 'invisible',
		]);
		return $el;
	}

}
