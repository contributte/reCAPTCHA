Nette-reCAPTCHA
===============

[![Build Status](https://travis-ci.org/f3l1x/Nette-reCAPTCHA.svg?branch=master)](https://travis-ci.org/f3l1x/Nette-reCAPTCHA)
[![Downloads this Month](https://img.shields.io/packagist/dm/f3l1x/nette-recaptcha.svg?style=flat)](https://packagist.org/packages/f3l1x/nette-recaptcha)
[![Latest stable](https://img.shields.io/packagist/v/f3l1x/nette-recaptcha.svg?style=flat)](https://packagist.org/packages/f3l1x/nette-recaptcha)

Google reCAPTCHA implementation for Nette Framework #nette #nettefw


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
    class: Forms\reCAPTCHA\ReCaptchaValidator(%reCAPTCHA.secretKey%)

reCAPTCHA.holder:
    factory: Forms\reCAPTCHA\ReCaptchaHolder::factory(%reCAPTCHA.siteKey%)
    tags: [run]
```

## Forms
```php
use Forms\reCAPTCHA\ReCaptchaField;
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
use Nette\Application\UI\Form;

protected function createComponentForm() 
{
    $form = new Form();
    
    $form['recaptcha'] = new ReCaptchaField($this->siteKey); 
}
```

# Install

```sh
$ composer require f3l1x/nette-recaptcha:dev-master
```

