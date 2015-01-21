Nette-reCAPTCHA
===============

[![Build Status](https://travis-ci.org/f3l1x/Nette-reCAPTCHA.svg?branch=master)](https://travis-ci.org/f3l1x/Nette-reCAPTCHA)
[![Downloads this Month](https://img.shields.io/packagist/dm/f3l1x/nette-recaptcha.svg?style=flat)](https://packagist.org/packages/f3l1x/nette-recaptcha)
[![Latest stable](https://img.shields.io/packagist/v/f3l1x/nette-recaptcha.svg?style=flat)](https://packagist.org/packages/f3l1x/nette-recaptcha)
[![Code Climate](https://codeclimate.com/github/f3l1x/Nette-reCAPTCHA/badges/gpa.svg)](https://codeclimate.com/github/f3l1x/Nette-reCAPTCHA)
[![HHVM Status](https://img.shields.io/hhvm/f3l1x/Nette-reCAPTCHA.svg?style=flat)](http://hhvm.h4cc.de/package/f3l1x/Nette-reCAPTCHA)

Google reCAPTCHA implementation for Nette Framework #nette #nettefw

# Install

```sh
$ composer require f3l1x/nette-recaptcha:dev-master
```

![reCAPTCHA](https://raw.githubusercontent.com/f3l1x/Nette-reCAPTCHA/master/recaptcha.png)

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
    class: Forms\reCAPTCHA\ReCaptchaValidator
    implement: Forms\reCAPTCHA\IReCaptchaValidatorFactory
    arguments: [%reCAPTCHA.secretKey%]

reCAPTCHA.holder:
    factory: Forms\reCAPTCHA\ReCaptchaHolder::factory(%reCAPTCHA.siteKey%)
    tags: [run]
```

## Forms
```php
use Forms\reCAPTCHA\ReCaptchaField;
use Forms\reCAPTCHA\ReCaptchaHolder;
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
use Forms\reCAPTCHA\ReCaptchaField;
use Forms\reCAPTCHA\IReCaptchaValidatorFactory;
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

