<?php declare(strict_types = 1);

namespace Contributte\ReCaptcha\Forms;

use Contributte\ReCaptcha\ReCaptchaProvider;
use Nette\Forms\Controls\TextInput;
use Nette\Forms\Form;
use Nette\Forms\Rules;
use Nette\Utils\Html;

class ReCaptchaField extends TextInput
{

	/** @var ReCaptchaProvider */
	private $provider;

	/** @var bool */
	private $configured = false;

	/** @var string|null */
	private $message;

	public function __construct(ReCaptchaProvider $provider, ?string $label = null, ?string $message = null)
	{
		parent::__construct($label);
		$this->provider = $provider;

		$this->setOmitted(true);
		$this->control = Html::el('div');
		$this->control->addClass('g-recaptcha');

		$this->message = $message;
	}

	public function loadHttpData(): void
	{
		$this->setValue($this->getForm()->getHttpData(Form::DATA_TEXT, ReCaptchaProvider::FORM_PARAMETER));
	}

	public function setMessage(string $message): self
	{
		$this->message = $message;

		return $this;
	}

	public function validate(): void
	{
		$this->configureValidation();
		parent::validate();
	}

	public function getRules(): Rules
	{
		$this->configureValidation();

		return parent::getRules();
	}

	private function configureValidation(): void
	{
		if ($this->configured) {
			return;
		}

		$message = $this->message ?? 'Are you a bot?';
		$this->addRule(function ($code): bool {
			return $this->verify() === true;
		}, $message);
		$this->configured = true;
	}

	public function verify(): bool
	{
		return $this->provider->validateControl($this) === true;
	}

	public function getControl(): Html
	{
		$this->configureValidation();

		$el = parent::getControl();
		$el->addAttributes([
			'id' => $this->getHtmlId(),
			'name' => $this->getHtmlName(),
			'data-sitekey' => $this->provider->getSiteKey(),
		]);

		return $el;
	}

}
