<?php

namespace Minetro\Forms\reCAPTCHA;

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
        'siteKey' => NULL,
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
        $config = $this->getConfig($this->defaults);
        $builder = $this->getContainerBuilder();
        $builder->addDefinition($this->prefix('validator'))
            ->setClass('Minetro\Forms\reCAPTCHA\ReCaptchaValidator', [$config['secretKey']]);
    }

    /**
     * @param ClassType $class
     */
    public function afterCompile(ClassType $class)
    {
        $config = $this->getConfig($this->defaults);
        $method = $class->methods['initialize'];
        if ($config['siteKey'] != NULL) {
            $method->addBody('Minetro\Forms\reCAPTCHA\ReCaptchaBinding::bind(?);', [$config['siteKey']]);
            $method->addBody('Minetro\Forms\reCAPTCHA\ReCaptchaHolder::factory(?);', [$config['siteKey']]);
        }
    }
}