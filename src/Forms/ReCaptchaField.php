<?php

namespace Minetro\ReCaptcha\Forms;

use Minetro\ReCaptcha\ReCaptchaProvider;
use Nette\Forms\Controls\TextInput;
use Nette\Forms\Form;
use Nette\Utils\Html;

/**
 * @author Milan Felix Sulc <sulcmil@gmail.com>
 */
class ReCaptchaField extends TextInput
{

    /** @var ReCaptchaProvider */
    private $provider;

    /**
     * @param ReCaptchaProvider $provider
     * @param string $label
     */
    public function __construct(ReCaptchaProvider $provider, $label = NULL)
    {
        parent::__construct($label);
        $this->provider = $provider;

        $this->control = Html::el('div');
        $this->control->addClass('g-recaptcha');
    }

    /**
     * Parse code from form data
     *
     * @return void
     */
    public function loadHttpData()
    {
        $this->setValue($this->getForm()->getHttpData(Form::DATA_TEXT, ReCaptchaProvider::FORM_PARAMETER));
    }

    /**
     * @param string $message
     * @return static
     */
    public function setMessage($message)
    {
        $this->addRule(function ($code) {
            return $this->verify() === TRUE;
        }, $message);

        return $this;
    }

    /**
     * @return bool
     */
    public function verify()
    {
        return $this->provider->validateControl($this) === TRUE;
    }

    /**
     * Create control
     *
     * @return Html
     */
    public function getControl()
    {
        $el = parent::getControl();
        $el->addAttributes([
            'id' => $this->getHtmlId(),
            'name' => $this->getHtmlName(),
            'data-sitekey' => $this->provider->getSiteKey(),
        ]);

        return $el;
    }

}
