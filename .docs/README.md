# Contributte reCAPTCHA

## Content

- [Pre-installation](#pre-installation)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Rendering](#rendering)
- [Invisible](#invisible)

## Pre-installation

Add your site to the sitelist in [reCAPTCHA administration](https://www.google.com/recaptcha/admin#list).

![reCAPTCHA](https://rawgit.com/contributte/reCAPTCHA/master/.docs/recaptcha.png)

## Installation

The latest version is most suitable for **Nette 2.4** and **PHP >=5.6**.

```bash

composer require contributte/recaptcha
```

## Configuration

```yaml
extensions:
    recaptcha: Contributte\ReCaptcha\DI\ReCaptchaExtension

recaptcha:
    secretKey: ***
    siteKey: ***
```

## Usage

```php
use Nette\Application\UI\Form;

protected function createComponentForm()
{
    $form = new Form();

    $form->addReCaptcha('recaptcha', $label = 'Captcha')
        ->setMessage('Are you a bot?');

    $form->addReCaptcha('recaptcha', $label = 'Captcha', $required = FALSE)
        ->setMessage('Are you a bot?');

    $form->addReCaptcha('recaptcha', $label = 'Captcha', $required = TRUE, $message = 'Are you a bot?');

    $form->onSuccess[] = function($form) {
        dump($form->getValues());
    }
}
```

## Rendering

```smarty
<form n:name="myForm">
	<div class="form-group">
		<div n:name="recaptcha"></div>
	</div>
</form>
```

Be sure to place this script before the closing tag of the `body` element (`</body>`).

```html
<!-- re-Captcha -->
<script src='https://www.google.com/recaptcha/api.js'></script>
```

## Invisible

![reCAPTCHA](https://rawgit.com/contributte/reCAPTCHA/master/.docs/invisible-recaptcha.png)

### Usage

```php
use Nette\Application\UI\Form;

protected function createComponentForm()
{
    $form = new Form();

    $form->addInvisibleReCaptcha('recaptcha')
        ->setMessage('Are you a bot?');

    $form->addInvisibleReCaptcha('recaptcha', $required = FALSE)
        ->setMessage('Are you a bot?');

    $form->addInvisibleReCaptcha('recaptcha', $required = TRUE, $message = 'Are you a bot?');

    $form->onSuccess[] = function($form) {
        dump($form->getValues());
    }
}
```

Be sure to place this script before the closing tag of the `body` element (`</body>`).

Copy [assets/invisibleRecaptcha.js](https://github.com/contributte/reCAPTCHA/blob/master/assets/invisibleRecaptcha.js) and link it.

```html
<script src="https://www.google.com/recaptcha/api.js?render=explicit"></script>
<script src="{$basePath}/assets/invisibleRecaptcha.js"></script>
```
