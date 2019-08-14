<?php
declare (strict_types=1);

namespace Project\Module\GenericValueObject;

use InvalidArgumentException;
use function strlen;

/**
 * Class Street
 * @package Project\Module\GenericValueObject
 */
class Street extends DefaultGenericValueObject
{
    protected const MIN_STREET_LENGTH = 5;

    /** @var string $street */
    protected $street;

    /**
     * Street constructor.
     *
     * @param string $street
     */
    protected function __construct(string $street)
    {
        $this->street = $street;
    }

    /**
     * @param string $street
     *
     * @return Street
     */
    public static function fromString(string $street): self
    {
        self::ensureStreetIsValid($street);

        return new self($street);
    }

    /**
     * @param string $street
     *
     */
    protected static function ensureStreetIsValid(string $street): void
    {
        if (strlen($street) < self::MIN_STREET_LENGTH) {
            throw new InvalidArgumentException('This street name is too short: ' . $street, 1);
        }

        if (preg_match('/\d/', $street) === 1) {
            throw new InvalidArgumentException('This street name is not valid: ' . $street, 1);
        }
    }

    /**
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->street;
    }
}

