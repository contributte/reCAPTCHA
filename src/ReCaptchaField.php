<?php

namespace Minetro\Forms\reCAPTCHA;

use Nette\Forms\Controls\BaseControl;
use Nette\Forms\Form;
use Nette\Utils\Html;

/**
 * @author Milan Felix Sulc <sulcmil@gmail.com>
 */
class ReCaptchaField extends BaseControl
{

    // Google POST parameter
    const GOOGLE_POST_PARAMETER = 'g-recaptcha-response';

    /** @var string */
    private $siteKey;

    /**
     * @param string $siteKey
     * @param string $label
     */
    public function __construct($siteKey, $label = NULL)
    {
        parent::__construct($label);

        $this->siteKey = $siteKey;

        $this->control = Html::el('div');
        $this->control->addClass('g-recaptcha');
    }

    /**
     * @param string $siteKey
     * @return void
     */
    public function setSiteKey($siteKey)
    {
        $this->siteKey = $siteKey;
    }

    /**
     * @return string
     */
    public function getSiteKey()
    {
        return $this->siteKey;
    }

    /**
     * Parse token from given parameters
     *
     * @override
     * @return void
     */
    public function loadHttpData()
    {
        $value = $this->getForm()->getHttpData(Form::DATA_TEXT, self::GOOGLE_POST_PARAMETER);
        $this->setValue($value);
    }

    /**
     * Create control
     *
     * @override
     * @return Html
     */
    public function getControl()
    {
        $el = parent::getControl();
        $el->addAttributes([
            'id' => $this->getHtmlId(),
            'name' => $this->getHtmlName(),
            'data-sitekey' => $this->getSiteKey(),
        ]);

        return $el;
    }

}
