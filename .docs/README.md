# Contributte reCAPTCHA

## Content

- [Pre-installation](#pre-installation)
- [Installation](#installation)
- [Configuration](#configuration)
- [Proxy Support](#proxy-support)
- [Custom HTTP Client](#custom-http-client)
- [Usage](#usage)
- [Rendering](#rendering)
- [Invisible](#invisible)

## Pre-installation

Add your site to the sitelist in [reCAPTCHA administration](https://www.google.com/recaptcha/admin#list).

![reCAPTCHA](https://rawgit.com/contributte/reCAPTCHA/master/.docs/recaptcha.png)

## Installation

The latest version is most suitable for **Nette 3.1+** and **PHP >=8.2**.

```bash
composer require contributte/recaptcha
```

Register prepared [compiler extension](https://doc.nette.org/en/dependency-injection/nette-container) in your `config.neon` file.

```neon
extensions:
	recaptcha: Contributte\ReCaptcha\DI\ReCaptchaExtension
```

## Configuration

### Minimal configuration

```neon
recaptcha:
	secretKey: ***
	siteKey: ***
```

### Advanced configuration

```neon
recaptcha:
	secretKey: ***
	siteKey: ***
	minimalScore: 0.5 # 0.0-1.0 v3 recaptcha threshold, 0.0 is likely a bot, 1.0 is likely a human
	timeout: 5 # request timeout in seconds
	retries: 3 # request retries
```

## Proxy Support

If your server is behind a proxy, you can configure the proxy URL in the configuration:

```neon
recaptcha:
	secretKey: ***
	siteKey: ***
	proxy: http://proxy.example.com:8080
```

### Proxy with authentication

```neon
recaptcha:
	secretKey: ***
	siteKey: ***
	proxy: http://username:password@proxy.example.com:8080
```

### Supported proxy URL formats

- `http://host:port` - HTTP proxy
- `tcp://host:port` - TCP format
- `host:port` - Simple format (defaults to tcp://)

## Custom HTTP Client

For advanced use cases, you can implement the `HttpClient` interface and inject your own HTTP client:

```php
use Contributte\ReCaptcha\Http\HttpClient;

class GuzzleHttpClient implements HttpClient
{

	public function __construct(
		private \GuzzleHttp\Client $client,
	)
	{
	}

	public function get(string $url): string|null
	{
		try {
			$response = $this->client->get($url);

			return $response->getBody()->getContents();
		} catch (\Throwable) {
			return null;
		}
	}

}
```

Then inject it into the provider:

```php
$provider = $container->getByType(ReCaptchaProvider::class);
$provider->setHttpClient(new GuzzleHttpClient($guzzleClient));
```

Or register it in the DI container:

```neon
services:
	recaptcha.httpClient:
		factory: App\GuzzleHttpClient

	recaptcha.provider:
		setup:
			- setHttpClient(@recaptcha.httpClient)
```

## Usage

```php
use Nette\Application\UI\Form;

protected function createComponentForm()
{
	$form = new Form();

	$form->addReCaptcha('recaptcha', $label = 'Captcha')
		->setMessage('Are you a bot?');

	$form->addReCaptcha('recaptcha', $label = 'Captcha', $required = FALSE)
		->setMessage('Are you a bot?');

	$form->addReCaptcha('recaptcha', $label = 'Captcha', $required = TRUE, $message = 'Are you a bot?');

	$form->onSuccess[] = function($form) {
		dump($form->getValues());
	}
}
```

## Rendering

```latte
<form n:name="myForm">
    <div class="form-group">
        <div n:name="recaptcha"></div>
    </div>
</form>
```

Be sure to place this script before the closing tag of the `body` element (`</body>`).

```html
<!-- re-Captcha -->
<script src='https://www.google.com/recaptcha/api.js'></script>
```

## Invisible

![reCAPTCHA](https://rawgit.com/contributte/reCAPTCHA/master/.docs/invisible-recaptcha.png)

### Usage

```php
use Nette\Application\UI\Form;

protected function createComponentForm()
{
	$form = new Form();

	$form->addInvisibleReCaptcha('recaptcha')
		->setMessage('Are you a bot?');

	$form->addInvisibleReCaptcha('recaptcha', $required = FALSE)
		->setMessage('Are you a bot?');

	$form->addInvisibleReCaptcha('recaptcha', $required = TRUE, $message = 'Are you a bot?');

	$form->onSuccess[] = function($form) {
		dump($form->getValues());
	}
}
```

Be sure to place this script before the closing tag of the `body` element (`</body>`).

Copy [assets/invisibleRecaptcha.js](https://github.com/contributte/reCAPTCHA/blob/master/assets/invisibleRecaptcha.js) and link it.

```html
<script src="https://www.google.com/recaptcha/api.js?render=explicit"></script>
<script src="{$basePath}/assets/invisibleRecaptcha.js"></script>
```
