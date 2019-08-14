<?php
declare(strict_types=1);

namespace Project\Module\GenericValueObject;

use InvalidArgumentException;
use Project\Configuration;
use function strlen;

/**
 * Class MobilePhone
 * @package     Project\Module\GenericValueObject
 */
class MobilePhone extends DefaultGenericValueObject
{
    /** @var string OUTPUT_DELIMETER */
    protected const OUTPUT_DELIMETER = '';

    /** @var string $mobilePhone */
    protected $mobilePhone;

    /** @var string $prefix */
    protected $prefix;

    /**
     * MobilePhone constructor.
     *
     * @param string $mobilePhone
     * @param string $prefix
     */
    protected function __construct(string $mobilePhone, string $prefix)
    {
        $this->prefix = $prefix;
        $this->mobilePhone = $mobilePhone;
    }

    /**
     * @param string        $mobilePhone
     *
     * @param Configuration $configuration
     *
     * @return MobilePhone
     */
    public static function fromString(string $mobilePhone, Configuration $configuration): self
    {
        $mobilePhone = self::convertMobilePhone($mobilePhone);

        self::ensureMobilePhoneIsValid($mobilePhone, $configuration);

        $prefix = self::getPrefix($mobilePhone);
        $phoneNumber = self::getPhoneNumber($mobilePhone);

        return new self($prefix, $phoneNumber);
    }

    /**
     * @param string        $mobilePhone
     *
     * @param Configuration $configuration
     */
    protected static function ensureMobilePhoneIsValid(string $mobilePhone, Configuration $configuration): void
    {
        $count = strlen($mobilePhone);

        if ($count < 11 || $count > 12) {
            throw new InvalidArgumentException('This mobile phone number is not valid: ' . $mobilePhone);
        }

        $prefix = self::getPrefix($mobilePhone);
        if (in_array($prefix, $configuration->getEntryByName('mobilePhonePrefixList'), false) === false) {
            throw new InvalidArgumentException('This mobile phone number has no valid prefix: ' . $mobilePhone);
        }
    }

    /**
     * @param string $mobilePhone
     *
     * @return string
     */
    protected static function getPrefix(string $mobilePhone): string
    {
        return substr($mobilePhone, 0, 4);
    }

    protected static function getPhoneNumber(string $mobilePhone): string
    {
        return substr($mobilePhone, 4);
    }

    /**
     * @param string $mobilePhone
     *
     * @return string
     */
    protected static function convertMobilePhone(string $mobilePhone): string
    {
        if (strpos($mobilePhone, '49') === 0) {
            $mobilePhone = '0' . substr($mobilePhone, 2, strlen($mobilePhone));
        }

        if (strpos($mobilePhone, '+49') === 0) {
            $mobilePhone = '0' . substr($mobilePhone, 3, strlen($mobilePhone));
        }

        if ($mobilePhone[0] !== '0') {
            $mobilePhone = '0' . $mobilePhone;
        }

        return preg_replace('/[() .+-]/', '', $mobilePhone);
    }

    /**
     * @return string
     */
    public function getMobilePhone(): string
    {
        return $this->prefix . self::OUTPUT_DELIMETER . $this->mobilePhone;
    }

    /**
     * @return string
     */

    public function __toString()
    {
        return $this->getMobilePhone();
    }
}