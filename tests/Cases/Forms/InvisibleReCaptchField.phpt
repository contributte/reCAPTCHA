<?php declare(strict_types = 1);

namespace Tests\Cases\Forms;

use Contributte\ReCaptcha\Forms\InvisibleReCaptchaField;
use Contributte\ReCaptcha\ReCaptchaProvider;
use Contributte\Tester\Toolkit;
use Nette\Forms\Controls\BaseControl;
use Nette\Forms\Form;
use Nette\Http\FileUpload;
use Nette\Utils\Html;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

final class FormMock extends Form
{

	public function getHttpData(?int $type = null, ?string $htmlName = null): FileUpload|array|string|null
	{
		return $htmlName;
	}

}

Toolkit::test(function (): void {
	$field = new InvisibleReCaptchaField(new ReCaptchaProvider('foobar', 'secret'));
	Assert::equal(['g-recaptcha' => true], $field->getControlPrototype()->getClass());

	$field->getControlPrototype()->addClass('foo');
	Assert::equal(['g-recaptcha' => true, 'foo' => true], $field->getControlPrototype()->getClass());

	$field->getControlPrototype()->class('foobar');
	Assert::equal('foobar', $field->getControlPrototype()->getClass());
});

Toolkit::test(function (): void {
	$form = new FormMock('form');

	$fieldName = 'captcha';
	$field = new InvisibleReCaptchaField(new ReCaptchaProvider('foobar', 'secret'));
	$form->addComponent($field, $fieldName);

	Assert::type(Html::class, $field->getControl());
	Assert::equal(sprintf(BaseControl::$idMask, $form->getName() . '-' . $fieldName), $field->getHtmlId());
});

Toolkit::test(function (): void {
	$form = new FormMock('form');

	$fieldName = 'captcha';
	$key = 'key';
	$field = new InvisibleReCaptchaField(new ReCaptchaProvider('key', 'secret'));
	$form->addComponent($field, $fieldName);

	Assert::equal($key, $field->getControl()->{'data-sitekey'});
});

Toolkit::test(function (): void {
	$form = new FormMock('form');

	$fieldName = 'captcha';
	$label = 'label';
	$field = new InvisibleReCaptchaField(new ReCaptchaProvider('key', 'secret'), $label);
	$form->addComponent($field, $fieldName);

	Assert::equal('', $field->getValue());

	$field->loadHttpData();
	Assert::equal(ReCaptchaProvider::FORM_PARAMETER, $field->getValue());
});
