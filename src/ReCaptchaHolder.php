<?php

namespace Forms\reCAPTCHA;

/**
 * reCAPTCHA Holder
 *
 * @author Milan Felix Sulc <sulcmil@gmail.com>
 */
final class ReCaptchaHolder
{

    /** @var string */
    private static $siteKey;

    /**
     * @param string $siteKey
     */
    private function __construct($siteKey)
    {
        self::$siteKey = $siteKey;
    }

    /**
     * @return string
     */
    public static function getSiteKey()
    {
        return self::$siteKey;
    }

    /**
     * @param string $siteKey
     * @return ReCaptchaHolder
     */
    public static function factory($siteKey)
    {
        return new self($siteKey);
    }
}