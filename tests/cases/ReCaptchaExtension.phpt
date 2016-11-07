<?php

/**
 * Test: ReCaptchaExtension
 */

use Minetro\Forms\reCAPTCHA\ReCaptchaExtension;
use Minetro\Forms\reCAPTCHA\ReCaptchaValidator;
use Nette\DI\Compiler;
use Nette\DI\ContainerLoader;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

test(function () {
    $loader = new ContainerLoader(TEMP_DIR);
    $class = $loader->load(function (Compiler $compiler) {
        $compiler->addExtension('captcha', new ReCaptchaExtension());
    }, 'SC1');

    $container = new $class;
    Assert::type(ReCaptchaValidator::class, $container->getByType(ReCaptchaValidator::class));
});

test(function () {
    $loader = new ContainerLoader(TEMP_DIR);
    $class = $loader->load(function (Compiler $compiler) {
        $compiler->addExtension('captcha', new ReCaptchaExtension());

        $compiler->addConfig([
            'captcha' => [
                'siteKey' => 'foobar',
            ],
        ]);
    }, 'SC2');

    $container = new $class;
    Assert::type(ReCaptchaValidator::class, $container->getByType(ReCaptchaValidator::class));
});
