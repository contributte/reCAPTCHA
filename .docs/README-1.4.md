# reCAPTCHA 1.4.x

## Installation

This version is suitable for Nette 2.2.

```bash
composer require minetro/recaptcha:~1.4.4
```

## Configuration

```neon
# reCAPTCHA
parameters:
	reCAPTCHA:
		siteKey: ***key**
		secretKey: ***key**
```

```neon
services:
	reCAPTCHA.validator:
		class: Minetro\Forms\reCAPTCHA\ReCaptchaValidator
		implement: Minetro\Forms\reCAPTCHA\IReCaptchaValidatorFactory
		arguments: [%reCAPTCHA.secretKey%]

	reCAPTCHA.holder:
		factory: Minetro\Forms\reCAPTCHA\ReCaptchaHolder::factory(%reCAPTCHA.siteKey%)
		tags: [run]
```

## Usage

```php
use Minetro\Forms\reCAPTCHA\ReCaptchaField;
use Minetro\Forms\reCAPTCHA\IReCaptchaValidatorFactory;
use Nette\Application\UI\Form;

/** @var IReCaptchaValidatorFactory @inject */
public $reCaptchaValidatorFactory;

/** @var string */
private $siteKey;

protected function createComponentForm()
{
	$form = new Form();

	$form['recaptcha'] = $recaptcha = new ReCaptchaField($this->siteKey, $label = NULL);

	$validator = $this->reCaptchaValidatorFactory->create();
	$recaptcha->addRule([$validator, 'validateControl'], 'You`re bot!');
}
```

## Rendering

```latte
<form n:name="myForm">
    <div class="form-group">
        <label n:name="captcha" class="required">Captcha</label>
        <div n:name="captcha"></div>
    </div>
</form>
```

Place this script before `</body>` element.

```html
<!-- re-Captcha -->
<script src='https://www.google.com/recaptcha/api.js'></script>
```
