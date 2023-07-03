<?php declare(strict_types = 1);

namespace Contributte\ReCaptcha;

final class ReCaptchaResponse
{

	// Error code list
	public const ERROR_CODE_MISSING_INPUT_SECRET = 'missing-input-secret';
	public const ERROR_CODE_INVALID_INPUT_SECRET = 'invalid-input-secret';
	public const ERROR_CODE_MISSING_INPUT_RESPONSE = 'missing-input-response';
	public const ERROR_CODE_INVALID_INPUT_RESPONSE = 'invalid-input-response';
	public const ERROR_CODE_UNKNOWN = 'unknow';

	private bool $success;

	/** @var string[]|string|null */
	private array|string|null $error = null;

	/**
	 * @param string[]|string|null $error
	 */
	public function __construct(bool $success, array|string|null $error = null)
	{
		$this->success = $success;
		$this->error = $error;
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

	public function __toString(): string
	{
		return (string) $this->isSuccess();
	}

}
