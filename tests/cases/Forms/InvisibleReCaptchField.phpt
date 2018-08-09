<?php

namespace Tests\Forms;

/**
 * Test: ReCaptchaField
 */

use Contributte\ReCaptcha\Forms\InvisibleReCaptchaField;
use Contributte\ReCaptcha\ReCaptchaProvider;
use Nette\Forms\Controls\BaseControl;
use Nette\Forms\Form;
use Nette\Utils\Html;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

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
	$field = new InvisibleReCaptchaField(new ReCaptchaProvider('foobar', NULL));
	Assert::equal(['g-recaptcha' => TRUE], $field->getControlPrototype()->getClass());

	$field->getControlPrototype()->addClass('foo');
	Assert::equal(['g-recaptcha' => TRUE, 'foo' => TRUE], $field->getControlPrototype()->getClass());

	$field->getControlPrototype()->class('foobar');
	Assert::equal('foobar', $field->getControlPrototype()->getClass());
});

test(function () {
	$form = new FormMock('form');

	$fieldName = 'captcha';
	$field = new InvisibleReCaptchaField(new ReCaptchaProvider('foobar', NULL));
	$form->addComponent($field, $fieldName);

	Assert::type(Html::class, $field->getControl());
	Assert::equal(sprintf(BaseControl::$idMask, $fieldName), $field->getHtmlId());
});

test(function () {
	$form = new FormMock('form');

	$fieldName = 'captcha';
	$key = 'key';
	$field = new InvisibleReCaptchaField(new ReCaptchaProvider('key', NULL));
	$form->addComponent($field, $fieldName);

	Assert::equal($key, $field->getControl()->{'data-sitekey'});
});

test(function () {
	$form = new FormMock('form');

	$fieldName = 'captcha';
	$label = 'label';
	$field = new InvisibleReCaptchaField(new ReCaptchaProvider('key', NULL), $label);
	$form->addComponent($field, $fieldName);

	Assert::equal('', $field->getValue());

	$field->loadHttpData();
	Assert::equal(ReCaptchaProvider::FORM_PARAMETER, $field->getValue());
});
