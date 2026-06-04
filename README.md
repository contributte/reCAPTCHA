![](https://heatbadger.now.sh/github/readme/contributte/recaptcha/)

<p align=center>
  <a href="https://github.com/contributte/reCAPTCHA/actions"><img src="https://badgen.net/github/checks/contributte/reCAPTCHA/master?cache=300"></a>
  <a href="https://coveralls.io/r/contributte/reCAPTCHA"><img src="https://badgen.net/coveralls/c/github/contributte/reCAPTCHA?cache=300"></a>
  <a href="https://packagist.org/packages/contributte/reCAPTCHA"><img src="https://badgen.net/packagist/dm/contributte/reCAPTCHA"></a>
  <a href="https://packagist.org/packages/contributte/reCAPTCHA"><img src="https://badgen.net/packagist/v/contributte/reCAPTCHA"></a>
</p>
<p align=center>
  <a href="https://packagist.org/packages/contributte/reCAPTCHA"><img src="https://badgen.net/packagist/php/contributte/reCAPTCHA"></a>
  <a href="https://github.com/contributte/reCAPTCHA"><img src="https://badgen.net/github/license/contributte/reCAPTCHA"></a>
  <a href="https://bit.ly/ctteg"><img src="https://badgen.net/badge/support/gitter/cyan"></a>
  <a href="https://bit.ly/cttfo"><img src="https://badgen.net/badge/support/forum/yellow"></a>
  <a href="https://contributte.org/partners.html"><img src="https://badgen.net/badge/sponsor/donations/F96854"></a>
</p>

<p align=center>
Website 🚀 <a href="https://contributte.org">contributte.org</a> | Contact 👨🏻‍💻 <a href="https://f3l1x.io">f3l1x.io</a> | Twitter 🐦 <a href="https://twitter.com/contributte">@contributte</a>
</p>

Google reCAPTCHA for Nette - Forms.

## Versions

| State       | Version | Branch   | Nette  | PHP     |
|-------------|---------|----------|--------|---------|
| dev         | `^4.1`  | `master` | `3.1+` | `>=8.2` |
| stable      | `^4.0`  | `master` | `3.1+` | `>=8.1` |

## Contents

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

![reCAPTCHA](.docs/recaptcha.png)

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

![reCAPTCHA](.docs/invisible-recaptcha.png)

### Invisible usage

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

## Development

See [how to contribute](https://contributte.org) to this package. This package is currently maintained by these authors.

<a href="https://github.com/f3l1x">
    <img width="80" height="80" src="https://avatars2.githubusercontent.com/u/538058?v=3&s=80">
</a>

-----

Consider to [support](https://contributte.org/partners) **contributte** development team.
Also thank you for using this package.
