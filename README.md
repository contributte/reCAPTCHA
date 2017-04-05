# reCAPTCHA

Google reCAPTCHA implementation for [Nette Framework](https://github.com/nette/forms) forms.

-----

[![Build Status](https://img.shields.io/travis/minetro/reCAPTCHA.svg?style=flat-square)](https://travis-ci.org/minetro/reCAPTCHA)
[![Code coverage](https://img.shields.io/coveralls/minetro/reCAPTCHA.svg?style=flat-square)](https://coveralls.io/r/minetro/reCAPTCHA)
[![HHVM Status](https://img.shields.io/hhvm/minetro/reCAPTCHA.svg?style=flat-square)](http://hhvm.h4cc.de/package/minetro/reCAPTCHA)
[![Licence](https://img.shields.io/packagist/l/minetro/recaptcha.svg?style=flat-square)](https://packagist.org/packages/minetro/recaptcha)

[![Downloads this Month](https://img.shields.io/packagist/dm/minetro/recaptcha.svg?style=flat-square)](https://packagist.org/packages/minetro/recaptcha)
[![Downloads total](https://img.shields.io/packagist/dt/minetro/recaptcha.svg?style=flat-square)](https://packagist.org/packages/minetro/recaptcha)
[![Latest stable](https://img.shields.io/packagist/v/minetro/recaptcha.svg?style=flat-square)](https://packagist.org/packages/minetro/recaptcha)
[![Latest unstable](https://img.shields.io/packagist/vpre/minetro/recaptcha.svg?style=flat-square)](https://packagist.org/packages/minetro/recaptcha)

## Discussion / Help

[![Join the chat](https://img.shields.io/gitter/room/minetro/nette.svg?style=flat-square)](https://gitter.im/minetro/nette?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

## Versions

| Branch | Composer   | Nette | PHP   | Readme |
|--------|------------|------ | ----- | -------|
| master | dev-master | 2.4   | >=5.6 | -      |
| latest | ^2.0.0     | 2.4   | >=5.6 | -      |
| 1.6.x  | ^1.6.3     | 2.4   | >=5.6 | [README-1.6](https://github.com/minetro/reCAPTCHA/blob/master/.docs/README-1.6.md) |
| 1.5.x  | ^1.5.2     | 2.3   | >=5.4 | [README-1.5](https://github.com/minetro/reCAPTCHA/blob/master/.docs/README-1.5.md) |
| 1.4.x  | ^1.4.4     | 2.2   | >=5.4 | [README-1.4](https://github.com/minetro/reCAPTCHA/blob/master/.docs/README-1.4.md) |
| 1.3.x  | ^1.3.0     | 2.1   | >=5.4 | [README-1.3](https://github.com/minetro/reCAPTCHA/blob/master/.docs/README-1.3.md) |

## Pre-installation

Add your site to the sitelist in [reCAPTCHA administration](https://www.google.com/recaptcha/admin#list).

![reCAPTCHA](https://rawgit.com/minetro/reCAPTCHA/master/.docs/recaptcha.png)

## Installation

The latest version is most suitable for **Nette ~2.4.0** and **PHP >=5.6**.

```sh
composer require minetro/recaptcha:^2.0.0
```

## Configuration

```yaml
extensions:
    recaptcha: Minetro\ReCaptcha\DI\ReCaptchaExtension

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
