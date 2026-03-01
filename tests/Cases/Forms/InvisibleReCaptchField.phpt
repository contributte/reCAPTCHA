<?php declare(strict_types = 1);

namespace Tests\Cases\Forms;

use Contributte\ReCaptcha\Forms\InvisibleReCaptchaField;
use Contributte\ReCaptcha\ReCaptchaProvider;
use Contributte\Tester\Toolkit;
use Mockery;
use Nette\Forms\Controls\BaseControl;
use Nette\Forms\Form;
use Nette\Forms\Rules;
use Nette\Http\FileUpload;
use Nette\Utils\Html;
use Nette\Utils\Json;
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

// getRules returns Rules and triggers configureValidation exactly once
Toolkit::test(function (): void {
	$field = new InvisibleReCaptchaField(new ReCaptchaProvider('key', 'secret'));

	$rules = $field->getRules();
	Assert::type(Rules::class, $rules);
	Assert::count(1, iterator_to_array($rules));

	// calling again must not add the rule a second time
	$field->getRules();
	Assert::count(1, iterator_to_array($rules));
});

// setMessage returns self and overrides constructor message
Toolkit::test(function (): void {
	$provider = Mockery::mock(ReCaptchaProvider::class, ['key', 'secret'])
		->shouldAllowMockingProtectedMethods()
		->makePartial();

	$provider->shouldReceive('makeRequest')
		->andReturn(Json::encode(['success' => false]));

	$form = new FormMock('form');
	$field = new InvisibleReCaptchaField($provider, 'Original message');
	$result = $field->setMessage('Custom message');
	$form->addComponent($field, 'recaptcha');

	$field->loadHttpData();
	$field->validate();

	Assert::same($field, $result);
	Assert::contains('Custom message', $field->getErrors());
	Assert::notContains('Original message', $field->getErrors());
});

// setMinimalScore returns self for valid score
Toolkit::test(function (): void {
	$field = new InvisibleReCaptchaField(new ReCaptchaProvider('key', 'secret'));
	$result = $field->setMinimalScore(0.5);
	Assert::same($field, $result);
});

// setMinimalScore throws for score above 1
Toolkit::test(function (): void {
	$field = new InvisibleReCaptchaField(new ReCaptchaProvider('key', 'secret'));
	Assert::exception(
		fn () => $field->setMinimalScore(1.1),
		\LogicException::class,
	);
});

// setMinimalScore throws for negative score
Toolkit::test(function (): void {
	$field = new InvisibleReCaptchaField(new ReCaptchaProvider('key', 'secret'));
	Assert::exception(
		fn () => $field->setMinimalScore(-0.1),
		\LogicException::class,
	);
});

Mockery::close();
