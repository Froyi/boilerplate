<?php
declare (strict_types=1);

namespace Project\Module\GenericValueObject;

use InvalidArgumentException;
use function strlen;

/**
 * Class City
 * @package Project\Module\GenericValueObject
 */
class City extends DefaultGenericValueObject
{
    protected const MIN_CITY_LENGTH = 3;

    /** @var string $city */
    protected $city;

    /**
     * City constructor.
     *
     * @param string $city
     */
    protected function __construct(string $city)
    {
        $this->city = $city;
    }

    /**
     * @param string $city
     *
     * @return City
     */
    public static function fromString(string $city): self
    {
        self::ensureCityIsValid($city);

        return new self($city);
    }

    /**
     * @param string $city
     *
     */
    protected static function ensureCityIsValid(string $city): void
    {
        if (empty($city) === true) {
            throw new InvalidArgumentException('The city is empty!', 1);
        }

        if (strlen($city) < self::MIN_CITY_LENGTH) {
            throw new InvalidArgumentException('The city is too short: ' . $city, 1);
        }
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getCity();
    }
}

