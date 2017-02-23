<?php

namespace Minetro\Forms\reCAPTCHA;

use Nette;
use Nette\DI\CompilerExtension;
use Nette\PhpGenerator\ClassType;

/**
 * reCAPTCHA Extension
 *
 * @author Milan Felix Sulc <sulcmil@gmail.com>
 */
final class ReCaptchaExtension extends CompilerExtension
{

    /** @var array */
    private $defaults = [
        'secretKey' => NULL,
        'siteKey' => NULL
    ];

    /**
     * @param mixed $secretKey
     */
    public function __construct($secretKey = NULL)
    {
        $this->defaults['secretKey'] = $secretKey;
    }

    public function loadConfiguration()
    {
        $config = $this->validateConfig($this->defaults);
        $builder = $this->getContainerBuilder();

        $builder->addDefinition($this->prefix('validator'))
            ->setClass('Minetro\Forms\reCAPTCHA\ReCaptchaValidator', [$config['secretKey']]);

        $builder->addDefinition($this->prefix('validatorFactory'))
            ->setImplement('Minetro\Forms\reCAPTCHA\IReCaptchaValidatorFactory')
            ->setFactory($builder->getDefinition($this->prefix('validator')));
    }

    /**
     * @param ClassType $class
     */
    public function afterCompile(ClassType $class)
    {
        $config = $this->validateConfig($this->defaults);
        $method = $class->getMethod('initialize');

        if ($config['siteKey'] != NULL) {
            $method->addBody('Minetro\Forms\reCAPTCHA\ReCaptchaBinding::bind(?);', [$config['siteKey']]);
            $method->addBody('Minetro\Forms\reCAPTCHA\ReCaptchaHolder::factory(?);', [$config['siteKey']]);
        }
    }

}
