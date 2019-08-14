<?php
declare(strict_types=1);

namespace Project\Module\GenericValueObject;


use InvalidArgumentException;
use function strlen;

/**
 * Class Telephone
 * @package     Project\Module\GenericValueObject
 */
class Telephone
{
    /** @var string $telephone */
    protected $telephone;

    /**
     * Telephone constructor.
     *
     * @param string $telephone
     */
    protected function __construct(string $telephone)
    {
        $this->telephone = $telephone;
    }

    /**
     * @param string $telephone
     *
     * @return Telephone
     */
    public static function fromString(string $telephone): self
    {
        if (strpos($telephone, '49') === 0) {
            $telephone = '0' . substr($telephone, 3, strlen($telephone));
        }
        if (isset($telephone[0]) && $telephone[0] !== '0') {
            $telephone = '0' . $telephone;
        }

        self::ensureTelephoneIsValid($telephone);

        return new self(self::convertTelephone($telephone));
    }

    /**
     * @param string $telephone
     *
     */
    protected static function ensureTelephoneIsValid(string $telephone): void
    {
        $cleared = preg_replace('/[() .+-]/', '', $telephone);
        $count = strlen($cleared);

        if ($count < 10 || $count > 14) {
            throw new InvalidArgumentException('This phone number is not valid: ' . $telephone);
        }
    }

    /**
     * @param string $telephone
     *
     * @return string
     */
    protected static function convertTelephone(string $telephone): string
    {
        return preg_replace('/[() .+-]/', '', $telephone);
    }

    /**
     * @return string
     */
    public function getTelephone(): string
    {
        return $this->telephone;
    }

    /**
     * @return string
     */

    public function __toString()
    {
        return $this->getTelephone();
    }
}