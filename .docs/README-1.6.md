# reCAPTCHA 1.6.x

## Installation

This version is suitable for Nette 2.4.

```bash
composer require minetro/recaptcha:~1.6.3
```

## Configuration

```yaml
extensions:
    recaptcha: Minetro\Forms\reCAPTCHA\ReCaptchaExtension

recaptcha:
    secretKey: ***
    siteKey: ***
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

```smarty
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
