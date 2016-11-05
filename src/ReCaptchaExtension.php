<?php

namespace Minetro\Forms\reCAPTCHA;

use Nette\DI\CompilerExtension;
use Nette\PhpGenerator\ClassType;

/**
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

    /**
     * Register services
     * @return void
     */
    public function loadConfiguration()
    {
        $config = $this->validateConfig($this->defaults);
        $builder = $this->getContainerBuilder();

        $builder->addDefinition($this->prefix('validator'))
            ->setClass(ReCaptchaValidator::class, [$config['secretKey']]);
    }

    /**
     * @param ClassType $class
     * @return void
     */
    public function afterCompile(ClassType $class)
    {
        $config = $this->validateConfig($this->defaults);
        $method = $class->getMethod('initialize');

        if ($config['siteKey'] != NULL) {
            $method->addBody(sprintf('%s::bind(?);', ReCaptchaBinding::class), [$config['siteKey']]);
            $method->addBody(sprintf('%::factory(?);', ReCaptchaHolder::class), [$config['siteKey']]);
        }
    }

}
