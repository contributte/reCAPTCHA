<?php

/**
 * Test: ReCaptchaExtension
 */

use Minetro\Forms\reCAPTCHA\ReCaptchaExtension;
use Nette\DI\Compiler;
use Nette\DI\ContainerLoader;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

test(function () {
    $loader = new ContainerLoader(TEMP_DIR);
    $class = $loader->load('SC1', function (Compiler $compiler) {
        $compiler->addExtension('captcha', new ReCaptchaExtension());
    });

    $container = new $class;
    Assert::type('Minetro\Forms\reCAPTCHA\ReCaptchaValidator', $container->getByType('Minetro\Forms\reCAPTCHA\ReCaptchaValidator'));
});

test(function () {
    $loader = new ContainerLoader(TEMP_DIR);
    $class = $loader->load('SC2', function (Compiler $compiler) {
        $compiler->addExtension('captcha', new ReCaptchaExtension());

        $compiler->addConfig([
            'captcha' => [
                'siteKey' => 'foobar',
            ],
        ]);
    });

    $container = new $class;
    Assert::type('Minetro\Forms\reCAPTCHA\ReCaptchaValidator', $container->getByType('Minetro\Forms\reCAPTCHA\ReCaptchaValidator'));
});
