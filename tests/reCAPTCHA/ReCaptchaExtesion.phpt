<?php

/**
 * Test: ReCaptchaExtension
 */

use Minetro\Forms\reCAPTCHA\ReCaptchaExtension;
use Nette\DI\Compiler;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

test(function () {
    $compiler = new Compiler;
    $compiler->addExtension('captcha', new ReCaptchaExtension);
    $code = $compiler->compile([], 'SC1', 'Nette\DI\Container');

    file_put_contents(TEMP_DIR . '/code1.php', "<?php\n\n$code");
    require TEMP_DIR . '/code1.php';

    $container = new SC1;
    Assert::type('Minetro\Forms\reCAPTCHA\ReCaptchaValidator', $container->getByType('Minetro\Forms\reCAPTCHA\ReCaptchaValidator'));
});

test(function () {
    $compiler = new Compiler;
    $compiler->addExtension('captcha', new ReCaptchaExtension);
    $code = $compiler->compile([
        'captcha' => [
            'siteKey' => 'foobar',
        ],
    ], 'SC2', 'Nette\DI\Container');

    file_put_contents(TEMP_DIR . '/code2.php', "<?php\n\n$code");
    require TEMP_DIR . '/code2.php';

    $container = new SC2;
    Assert::type('Minetro\Forms\reCAPTCHA\ReCaptchaValidator', $container->getByType('Minetro\Forms\reCAPTCHA\ReCaptchaValidator'));
});
