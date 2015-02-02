reCAPTCHA
===============

[![Build Status](https://travis-ci.org/minetro/recaptcha.svg?branch=master)](https://travis-ci.org/minetro/recaptcha)
[![Downloads this Month](https://img.shields.io/packagist/dm/minetro/recaptcha.svg?style=flat)](https://packagist.org/packages/minetro/recaptcha)
[![Latest stable](https://img.shields.io/packagist/v/minetro/recaptcha.svg?style=flat)](https://packagist.org/packages/minetro/recaptcha)
[![Code Climate](https://codeclimate.com/github/minetro/recaptcha/badges/gpa.svg)](https://codeclimate.com/github/minetro/recaptcha)
[![HHVM Status](https://img.shields.io/hhvm/minetro/recaptcha.svg?style=flat)](http://hhvm.h4cc.de/package/minetro/recaptcha)

Google reCAPTCHA implementation for Nette Framework #nette #nettefw

# Install

```sh
$ composer require minetro/recaptcha:dev-master
```

![reCAPTCHA](https://raw.githubusercontent.com/minetro/recaptcha/master/recaptcha.png)

# Configuration

## NEON - parameters
```neon
# reCAPTCHA
reCAPTCHA:
    siteKey: ***key**
    secretKey: ***key**
```

## NEON - services
```neon
reCAPTCHA.validator:
    class: Minetro\Forms\reCAPTCHA\ReCaptchaValidator
    implement: Minetro\Forms\reCAPTCHA\IReCaptchaValidatorFactory
    arguments: [%reCAPTCHA.secretKey%]

reCAPTCHA.holder:
    factory: Minetro\Forms\reCAPTCHA\ReCaptchaHolder::factory(%reCAPTCHA.siteKey%)
    tags: [run]
```

## Forms
```php
use Minetro\Forms\reCAPTCHA\ReCaptchaField;
use Minetro\Forms\reCAPTCHA\ReCaptchaHolder;
use Nette\Application\UI\Form;

class MyForm extends Form
{

    /**
     * @return ReCaptchaField
     */
    public function addReCaptcha($name = 'recaptcha')
    {
        return $this[$name] = new ReCaptchaField(ReCaptchaHolder::getSiteKey());
    }

}
```

## Presenter/Control
```php
use Minetro\Forms\reCAPTCHA\ReCaptchaField;
use Minetro\Forms\reCAPTCHA\IReCaptchaValidatorFactory;
use Nette\Application\UI\Form;

/** @var IReCaptchaValidatorFactory @inject */
public $reCaptchaValidatorFactory;

/**
 * @return Form
 */
protected function createComponentForm() 
{
    $form = new Form();
    
    $form['recaptcha'] = $recaptcha = new ReCaptchaField($this->siteKey); 
    
    $validator = $this->reCaptchaValidatorFactory->create();
    $recaptcha->addRule([$validator, 'validateControl'], 'Vypadá to, že nejste člověk.');
    
    // ...
}
```

## JavaScript

Before `</body>` element.

```html
<!-- re-Captcha -->
<script src='https://www.google.com/recaptcha/api.js'></script>
```

