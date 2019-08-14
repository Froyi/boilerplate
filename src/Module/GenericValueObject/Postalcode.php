<?php
declare (strict_types=1);

namespace Project\Module\GenericValueObject;

use InvalidArgumentException;
use function strlen;

/**
 * Class Postalcode
 * @package Project\Module\GenericValueObject
 */
class Postalcode extends DefaultGenericValueObject
{
    protected const LENGTH_POSTALCODE = 5;

    /** @var string $postalcode */
    protected $postalcode;

    /**
     * Postalcode constructor.
     *
     * @param string $postalcode
     */
    protected function __construct(string $postalcode)
    {
        $this->postalcode = $postalcode;
    }

    /**
     * @param string $postalcode
     *
     * @return Postalcode
     * @throws InvalidArgumentException
     */
    public static function fromValue($postalcode): self
    {
        self::ensurePostalcodeIsValid($postalcode);

        $postalcode = self::convertPostalcode($postalcode);

        return new self($postalcode);
    }

    /**
     * @param $postalcode
     *
     * @throws InvalidArgumentException
     */
    protected static function ensurePostalcodeIsValid($postalcode): void
    {
        $postalcode = (string)$postalcode;

        if (strlen($postalcode) !== self::LENGTH_POSTALCODE) {
            throw new InvalidArgumentException('The postalcode is not valid: ' . $postalcode);
        }
    }

    /**
     * @param $postalcode
     *
     * @return string
     */
    protected static function convertPostalcode($postalcode): string
    {
        return (string)$postalcode;
    }

    /**
     * @return string
     */
    public function getPostalcode(): string
    {
        return $this->postalcode;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getPostalcode();
    }
}

