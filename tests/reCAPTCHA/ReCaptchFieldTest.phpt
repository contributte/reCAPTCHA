<?php

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
    public function getHttpData($type = NULL, $htmlName = NULL)
    {
        return $htmlName;
    }
}

test(function () {
    $field = new ReCaptchaField();
    Assert::null($field->getSiteKey());
});

test(function () {
    $key = 'key';
    $field = new ReCaptchaField($key);
    Assert::equal($key, $field->getSiteKey());
});

test(function () {
    $field = new ReCaptchaField();
    Assert::null($field->getSiteKey());

    $key = 'key';
    $field->setSiteKey($key);
    Assert::equal($key, $field->getSiteKey());
});

test(function () {
    $form = new FormMock('form');

    $fieldName = 'captcha';
    $field = new ReCaptchaField();
    $form->addComponent($field, $fieldName);

    Assert::true($field->getControl() instanceof Html);
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
    $field = new ReCaptchaField($key);
    $form->addComponent($field, $fieldName);

    Assert::equal('', $field->getValue());

    $field->loadHttpData();
    Assert::equal($field::GOOGLE_POST_PARAMETER, $field->getValue());
});
