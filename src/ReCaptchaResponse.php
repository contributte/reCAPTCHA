<?php declare(strict_types = 1);

namespace Contributte\ReCaptcha;

/**
 * @phpstan-type ReCaptchaResponseData array{
 *   success: bool,
 *   challenge_ts?: string,
 *   hostname?: string,
 *   'error-codes'?: string[],
 *   score?: float,
 *   action?: string,
 * }
 */
final class ReCaptchaResponse
{

	// Error code list
	public const ERROR_CODE_MISSING_INPUT_SECRET = 'missing-input-secret';
	public const ERROR_CODE_INVALID_INPUT_SECRET = 'invalid-input-secret';
	public const ERROR_CODE_MISSING_INPUT_RESPONSE = 'missing-input-response';
	public const ERROR_CODE_INVALID_INPUT_RESPONSE = 'invalid-input-response';
	public const ERROR_CODE_BAD_REQUEST = 'bad-request';
	public const ERROR_CODE_TIMEOUT_OR_DUPLICATE = 'timeout-or-duplicate';
	public const ERROR_CODE_UNKNOWN = 'unknown';

	private bool $success;

	/** @var string[]|string|null */
	private array|string|null $error;

	/** @var ReCaptchaResponseData|null */
	private ?array $data;

	/**
	 * @param string[]|string|null $error
	 * @param ReCaptchaResponseData|null $data
	 */
	public function __construct(bool $success, array|string|null $error = null, ?array $data = null)
	{
		$this->success = $success;
		$this->error = $error;
		$this->data = $data;
	}

	public function isSuccess(): bool
	{
		return $this->success;
	}

	/**
	 * @return string[]|string|null
	 */
	public function getError(): array|string|null
	{
		return $this->error;
	}

	/**
	 * @return ?ReCaptchaResponseData
	 */
	public function getData(): ?array
	{
		return $this->data;
	}

	public function __toString(): string
	{
		return (string) $this->isSuccess();
	}

}
