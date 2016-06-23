# reCAPTCHA

Google reCAPTCHA implementation for Nette Framework.

-----

[![Build Status](https://img.shields.io/travis/minetro/reCAPTCHA.svg?style=flat-square)](https://travis-ci.org/minetro/reCAPTCHA)
[![Code coverage](https://img.shields.io/coveralls/minetro/reCAPTCHA.svg?style=flat-square)](https://coveralls.io/r/minetro/reCAPTCHA)
[![Downloads this Month](https://img.shields.io/packagist/dm/minetro/recaptcha.svg?style=flat-square)](https://packagist.org/packages/minetro/recaptcha)
[![Downloads total](https://img.shields.io/packagist/dt/minetro/recaptcha.svg?style=flat-square)](https://packagist.org/packages/minetro/recaptcha)
[![Latest stable](https://img.shields.io/packagist/v/minetro/recaptcha.svg?style=flat-square)](https://packagist.org/packages/minetro/recaptcha)
[![HHVM Status](https://img.shields.io/hhvm/minetro/reCAPTCHA.svg?style=flat-square)](http://hhvm.h4cc.de/package/minetro/reCAPTCHA)

## Discussion / Help

[![Join the chat](https://img.shields.io/gitter/room/minetro/nette.svg?style=flat-square)](https://gitter.im/minetro/nette?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

## Pre-install

Add your site to the sitelist in reCAPTCHA administration.

https://www.google.com/recaptcha/admin#list

## Install

### Nette >=2.3
```sh
$ composer require minetro/recaptcha:~1.5.0
```

### Nette 2.2 
```sh
$ composer require minetro/recaptcha:~1.4.0
```

![reCAPTCHA](https://raw.githubusercontent.com/minetro/recaptcha/master/recaptcha.png)

# Configuration

### Automatic
```yaml
extensions:
    recaptcha: Minetro\Forms\reCAPTCHA\ReCaptchaExtension
    
recaptcha:
    secretKey: ***
    siteKey: ***
```
### Manual

#### NEON - parameters
```yaml
# reCAPTCHA
parameters:
    reCAPTCHA:
        siteKey: ***key**
        secretKey: ***key**
```

#### NEON - services
```yaml
services:
    reCAPTCHA.validator:
        class: Minetro\Forms\reCAPTCHA\ReCaptchaValidator
        implement: Minetro\Forms\reCAPTCHA\IReCaptchaValidatorFactory
        arguments: [%reCAPTCHA.secretKey%]
    
    reCAPTCHA.holder:
        factory: Minetro\Forms\reCAPTCHA\ReCaptchaHolder::factory(%reCAPTCHA.siteKey%)
        tags: [run]
```

You should call `ReCaptchaBinding::bind(%reCAPTCHA.siteKey%)`, if you want use native `$form->addReCaptcha()` method standalone.

## Usage 

### Forms

```php
use Minetro\Forms\reCAPTCHA\ReCaptchaField;
use Minetro\Forms\reCAPTCHA\ReCaptchaHolder;
use Nette\Application\UI\Form;

class MyForm extends Form
{

    /**
     * @param  string  $name   Field name
     * @param  string  $label  Html label
     * @return ReCaptchaField
     */
    public function addReCaptcha($name = 'recaptcha', $label = NULL)
    {
        return $this[$name] = new ReCaptchaField(ReCaptchaHolder::getSiteKey(), $label);
    }

}
```

```php
use Minetro\Forms\reCAPTCHA\ReCaptchaField;
use Minetro\Forms\reCAPTCHA\ReCaptchaHolder;
use Minetro\Forms\reCAPTCHA\IReCaptchaValidatorFactory;
use Nette\Application\UI\Form;

class MyAutoForm extends Form
{

    /** @var IReCaptchaValidatorFactory */
    private $validatorFactory;

    /**
     * @param IReCaptchaValidatorFactory $validatorFactory
     */
    public function __constructor(IReCaptchaValidatorFactory $validatorFactory) 
    {
        $this->validatorFactory = $validatorFactory;
    }

    /**
     * @param  string  $name   Field name
     * @param  string  $label  Html label
     * @return ReCaptchaField
     */
    public function addReCaptcha($name = 'recaptcha', $label = NULL)
    {
        $recaptcha = $this[$name] = new ReCaptchaField(ReCaptchaHolder::getSiteKey(), $label);

        $validator = $this->reCaptchaValidatorFactory->create();
        $recaptcha->addRule([$validator, 'validateControl'], 'You`re bot!');

        return $recaptcha;
    }

}

interface IMyAutoFormFactory 
{

    /**
     * @return MyAutoForm
     */
    public function create();

}
```

`ReCaptchaField` needs google.siteKey in constructor. You could handle it by yourself or use `ReCaptchaHolder::getSiteKey()`.

### Controls

#### Manually

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

#### Half automatic

```php
use Minetro\Forms\reCAPTCHA\ReCaptchaField;
use Minetro\Forms\reCAPTCHA\IReCaptchaValidatorFactory;
use Nette\Application\UI\Form;

/** @var IReCaptchaValidatorFactory @inject */
public $reCaptchaValidatorFactory;

protected function createComponentMyForm() 
{
    $form = new MyForm();
    
    $recaptcha = $form->addReCaptcha($name, $label);
    
    $validator = $this->reCaptchaValidatorFactory->create();
    $recaptcha->addRule([$validator, 'validateControl'], 'You`re bot!');
}
```

#### Full automatic

```php
use Minetro\Forms\reCAPTCHA\ReCaptchaField;
use Minetro\Forms\reCAPTCHA\IReCaptchaValidatorFactory;
use Nette\Application\UI\Form;

/** @var IReCaptchaValidatorFactory @inject */
public $reCaptchaValidatorFactory;

/** @var IMyAutoFormFactory @inject */
public $myAutoFormFactory;

protected function createComponentMyAutoForm() 
{
    $form = $this->myAutoFormFactory->create();
    
    $form->addReCaptcha($name, $label);
}
```

### Rendering

#### Manual 

```smarty
<form n:name="myForm">
	<div class="form-group">
		<label n:name="captcha" class="required">Captcha</label>
		<div n:name="captcha"></div>
	</div>
</form>
```

### JavaScript

Before `</body>` element.

```html
<!-- re-Captcha -->
<script src='https://www.google.com/recaptcha/api.js'></script>
```

