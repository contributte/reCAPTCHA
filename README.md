# reCAPTCHA

Google reCAPTCHA implementation for [Nette Framework](https://github.com/nette/forms) forms.

-----

[![Build Status](https://img.shields.io/travis/contributte/reCAPTCHA.svg?style=flat-square)](https://travis-ci.org/contributte/reCAPTCHA)
[![Code coverage](https://img.shields.io/coveralls/contributte/reCAPTCHA.svg?style=flat-square)](https://coveralls.io/r/contributte/reCAPTCHA)
[![Licence](https://img.shields.io/packagist/l/contributte/reCAPTCHA.svg?style=flat-square)](https://packagist.org/packages/contributte/reCAPTCHA)

[![Downloads this Month](https://img.shields.io/packagist/dm/contributte/reCAPTCHA.svg?style=flat-square)](https://packagist.org/packages/contributte/reCAPTCHA)
[![Downloads total](https://img.shields.io/packagist/dt/contributte/reCAPTCHA.svg?style=flat-square)](https://packagist.org/packages/contributte/reCAPTCHA)
[![Latest stable](https://img.shields.io/packagist/v/contributte/reCAPTCHA.svg?style=flat-square)](https://packagist.org/packages/contributte/reCAPTCHA)

## Discussion / Help

[![Join the chat](https://img.shields.io/gitter/room/contributte/nette.svg?style=flat-square)](https://gitter.im/contributte/nette?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

## Versions

| Branch | Composer   | Nette | PHP   | Readme |
|--------|------------|------ | ----- | -------|
| latest | ~3.0.0     | 2.4   | >=5.6 | -      |
| 2.x    | ~2.1.0     | 2.4   | >=5.6 | [README-2.x](https://github.com/contributte/reCAPTCHA/blob/master/.docs/README-2.x.md) |
| 1.6.x  | ~1.6.3     | 2.4   | >=5.6 | [README-1.6](https://github.com/contributte/reCAPTCHA/blob/master/.docs/README-1.6.md) |
| 1.5.x  | ~1.5.2     | 2.3   | >=5.4 | [README-1.5](https://github.com/contributte/reCAPTCHA/blob/master/.docs/README-1.5.md) |
| 1.4.x  | ~1.4.4     | 2.2   | >=5.4 | [README-1.4](https://github.com/contributte/reCAPTCHA/blob/master/.docs/README-1.4.md) |
| 1.3.x  | ~1.3.0     | 2.1   | >=5.4 | [README-1.3](https://github.com/contributte/reCAPTCHA/blob/master/.docs/README-1.3.md) |

## Pre-installation

Add your site to the sitelist in [reCAPTCHA administration](https://www.google.com/recaptcha/admin#list).

![reCAPTCHA](https://rawgit.com/contributte/reCAPTCHA/master/.docs/recaptcha.png)

## Installation

The latest version is most suitable for **Nette 2.4** and **PHP >=5.6**.

```sh
composer require contributte/recaptcha:~2.1.0
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
        ->setMessage('Are you bot?');
    
    $form->addReCaptcha('recaptcha', $label = 'Captcha', $required = FALSE)
        ->setMessage('Are you bot?');
    
    $form->addReCaptcha('recaptcha', $label = 'Captcha', $required = TRUE, $message = 'Are you bot?');
    
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

Be sure you place this script before `</body>` element.

```html
<!-- re-Captcha -->
<script src='https://www.google.com/recaptcha/api.js'></script>
```
