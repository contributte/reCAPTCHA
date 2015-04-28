<?php

namespace Minetro\Forms\reCAPTCHA;

use Nette\Forms\Controls\BaseControl;
use Nette\Forms\Controls\HiddenField;
use Nette\Forms\Form;
use Nette\Utils\Html;

/**
 * reCAPTCHA Field
 *
 * @author Milan Felix Sulc <sulcmil@gmail.com>
 */
final class ReCaptchaField extends HiddenField
{

    /** Google POST parameter */
    const GOOGLE_POST_PARAMETER = 'g-recaptcha-response';

    /** @var string */
    private $siteKey;

    /**
     * @param string $siteKey
     */
    public function __construct($siteKey = NULL)
    {
        parent::__construct();

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
     * Create label
	 *
     * @param mixed $caption
     * @return Html|string
     */
    public function getLabel($caption = NULL)
    {
        return BaseControl::getLabel($caption);
    }

    /**
     * Create control
     *
     * @override
     * @return Html
     */
    public function getControl()
    {
        $this->setOption('rendered', TRUE);

        $el = clone $this->control;
        $el->addAttributes([
            'id' => $this->getHtmlId(),
            'name' => $this->getHtmlName(),
            'data-sitekey' => $this->getSiteKey(),
        ]);

        return $el;
    }


}
