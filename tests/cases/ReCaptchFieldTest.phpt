<?php

namespace Tests;

/**
 * Test: ReCaptchaField
 */

use Minetro\Forms\reCAPTCHA\ReCaptchaField;
use Nette\Forms\Controls\BaseControl;
use Nette\Forms\Form;
use Nette\Utils\Html;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

final class FormMock extends Form
{

    /**
     * @param string $type
     * @param string $htmlName
     * @return mixed
     */
    public function getHttpData($type = NULL, $htmlName = NULL)
    {
        return $htmlName;
    }

}

test(function () {
    $field = new ReCaptchaField(NULL);
    Assert::null($field->getSiteKey());
});

test(function () {
    $key = 'key';
    $field = new ReCaptchaField($key);
    Assert::equal($key, $field->getSiteKey());
});

test(function () {
    $field = new ReCaptchaField('foobar');
    Assert::equal(['g-recaptcha' => TRUE], $field->getControlPrototype()->getClass());

    $field->getControlPrototype()->addClass('foo');
    Assert::equal(['g-recaptcha' => TRUE, 'foo' => TRUE], $field->getControlPrototype()->getClass());

    $field->getControlPrototype()->class('foobar');
    Assert::equal('foobar', $field->getControlPrototype()->getClass());
});

test(function () {
    $field = new ReCaptchaField(NULL);
    Assert::null($field->getSiteKey());

    $key = 'key';
    $field->setSiteKey($key);
    Assert::equal($key, $field->getSiteKey());
});

test(function () {
    $form = new FormMock('form');

    $fieldName = 'captcha';
    $field = new ReCaptchaField('foobar');
    $form->addComponent($field, $fieldName);

    Assert::type(Html::class, $field->getControl());
    Assert::type(Html::class, $field->getLabel());
    Assert::equal(sprintf(BaseControl::$idMask, $fieldName), $field->getHtmlId());
});

test(function () {
    $form = new FormMock('form');

    $fieldName = 'captcha';
    $key = 'key';
    $field = new ReCaptchaField($key);
    $form->addComponent($field, $fieldName);

    Assert::equal($key, $field->getSiteKey());
    Assert::equal($key, $field->getControl()->{'data-sitekey'});
});

test(function () {
    $form = new FormMock('form');

    $fieldName = 'captcha';
    $key = 'key';
    $label = 'label';
    $field = new ReCaptchaField($key, $label);
    $form->addComponent($field, $fieldName);

    Assert::null($field->getValue());
    Assert::same($label, $field->caption);

    $field->loadHttpData();
    Assert::equal($field::GOOGLE_POST_PARAMETER, $field->getValue());
});
