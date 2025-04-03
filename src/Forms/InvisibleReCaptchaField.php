<?php declare(strict_types = 1);

namespace Contributte\ReCaptcha\Forms;

use Contributte\ReCaptcha\ReCaptchaProvider;
use Nette\Forms\Controls\HiddenField;
use Nette\Forms\Form;
use Nette\Forms\Rules;
use Nette\Utils\Html;

class InvisibleReCaptchaField extends HiddenField
{

	private ReCaptchaProvider $provider;

	private bool $configured = false;

	private ?string $message = null;

	public function __construct(ReCaptchaProvider $provider, ?string $message = null)
	{
		parent::__construct();

		$this->provider = $provider;

		$this->setOmitted(true);
		$this->control = Html::el('div');
		$this->control->addClass('g-recaptcha');

		$this->message = $message;
	}

	public function loadHttpData(): void
	{
		parent::loadHttpData();

		$form = $this->getForm();
		assert($form !== null);
		$this->setValue($form->getHttpData(Form::DataText, ReCaptchaProvider::FORM_PARAMETER));
	}

	public function setMessage(string $message): self
	{
		$this->message = $message;

		return $this;
	}

	public function setMinimalScore(float $score): self
	{
		if ($score < 0 || $score > 1) {
			throw new \LogicException('Minimal score expects to be in range 0..1 (1.0 is very likely a good interaction, 0.0 is very likely a bot).');
		}

		$this->provider->setMinimalScore($score);

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

	public function verify(): bool
	{
		return $this->provider->validateControl($this) === true;
	}

	public function getControl(?string $caption = null): Html
	{
		$this->configureValidation();

		$el = parent::getControl();
		$el->addAttributes([
			'data-sitekey' => $this->provider->getSiteKey(),
			'data-size' => 'invisible',
		]);

		return $el;
	}

	private function configureValidation(): void
	{
		if ($this->configured) {
			return;
		}

		$message = $this->message ?? 'Are you a bot?';
		$this->addRule(fn ($code): bool => $this->verify() === true, $message);
		$this->configured = true;
	}

}
