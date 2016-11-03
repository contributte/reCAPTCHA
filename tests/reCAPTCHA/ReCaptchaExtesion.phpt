<?php

/**
 * Test: ReCaptchaExtension
 */

use Minetro\Forms\reCAPTCHA\ReCaptchaExtension;
use Nette\DI\Compiler;
use Nette\DI\ContainerFactory;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

test(function () {
    $loader = new ContainerFactory(TEMP_DIR);
    $loader->class = 'SC1' . time();
    $loader->onCompile[] = function (ContainerFactory $factory, Compiler $compiler) {
        $compiler->addExtension('captcha', new ReCaptchaExtension());
    };

    $container = $loader->create();
    Assert::type('Minetro\Forms\reCAPTCHA\ReCaptchaValidator', $container->getByType('Minetro\Forms\reCAPTCHA\ReCaptchaValidator'));
});

test(function () {
    $loader = new ContainerFactory(TEMP_DIR);
    $loader->class = 'SC2' . time();
    $loader->onCompile[] = function (ContainerFactory $factory, Compiler $compiler) {
        $compiler->addExtension('captcha', new ReCaptchaExtension());
    };

    $loader->config = [
        'captcha' => [
            'siteKey' => 'foobar',
        ],
    ];

    $container = $loader->create();
    Assert::type('Minetro\Forms\reCAPTCHA\ReCaptchaValidator', $container->getByType('Minetro\Forms\reCAPTCHA\ReCaptchaValidator'));
});
