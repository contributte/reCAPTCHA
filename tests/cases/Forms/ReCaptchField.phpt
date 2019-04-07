<?php declare(strict_types = 1);

namespace Tests\Cases\Forms;

/**
 * Test: ReCaptchaField
 */

use Contributte\ReCaptcha\Forms\ReCaptchaField;
use Contributte\ReCaptcha\ReCaptchaProvider;
use Nette\Forms\Controls\BaseControl;
use Nette\Forms\Form;
use Nette\Utils\Html;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

final class FormMock extends Form
{

	/**
	 * @return mixed
	 */
	public function getHttpData(?int $type = null, ?string $htmlName = null)
	{
		return $htmlName;
	}

}

test(function () {
	$field = new ReCaptchaField(new ReCaptchaProvider('foobar', 'secret'));
	Assert::equal(['g-recaptcha' => true], $field->getControlPrototype()->getClass());

	$field->getControlPrototype()->addClass('foo');
	Assert::equal(['g-recaptcha' => true, 'foo' => true], $field->getControlPrototype()->getClass());

	$field->getControlPrototype()->class('foobar');
	Assert::equal('foobar', $field->getControlPrototype()->getClass());
});

test(function () {
	$form = new FormMock('form');

	$fieldName = 'captcha';
	$field = new ReCaptchaField(new ReCaptchaProvider('foobar', 'secret'));
	$form->addComponent($field, $fieldName);

	Assert::type(Html::class, $field->getControl());
	Assert::type(Html::class, $field->getLabel());
	Assert::equal(sprintf(BaseControl::$idMask, $form->getName() . '-' . $fieldName), $field->getHtmlId());
});

test(function () {
	$form = new FormMock('form');

	$fieldName = 'captcha';
	$key = 'key';
	$field = new ReCaptchaField(new ReCaptchaProvider('key', 'secret'));
	$form->addComponent($field, $fieldName);

	Assert::equal($key, $field->getControl()->{'data-sitekey'});
});

test(function () {
	$form = new FormMock('form');

	$fieldName = 'captcha';
	$label = 'label';
	$field = new ReCaptchaField(new ReCaptchaProvider('key', 'secret'), $label);
	$form->addComponent($field, $fieldName);

	Assert::equal('', $field->getValue());
	Assert::same($label, $field->caption);

	$field->loadHttpData();
	Assert::equal(ReCaptchaProvider::FORM_PARAMETER, $field->getValue());
});
