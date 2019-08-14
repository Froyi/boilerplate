<?php
declare(strict_types=1);

namespace Project\Module\GenericValueObject;

use InvalidArgumentException;
use function is_int;
use function is_string;

/**
 * Class Distance
 * @package Project\Module\GenericValueObject
 */
class Distance
{
    /** @var int */
    protected $distance;

    /**
     * Distance constructor.
     *
     * @param int $distance
     */
    protected function __construct(int $distance)
    {
        $this->distance = $distance;
    }

    /**
     * @param $value
     * @param bool|null $isMeter
     *
     * @return Distance
     */
    public static function fromValue($value, ?bool $isMeter = true): self
    {
        self::ensureValueIsValid($value);

        return new self(self::convertValue($value, $isMeter));
    }

    /**
     * @param $value
     *
     * @throws InvalidArgumentException
     */
    protected static function ensureValueIsValid($value): void
    {
        if (is_int($value) === false && is_string($value) === false) {
            throw new InvalidArgumentException('This value is neither an int nor a string.');
        }
    }

    /**
     * @param $value
     * @param bool $isMeter
     *
     * @return int
     */
    protected static function convertValue($value, bool $isMeter): int
    {
        $value = (float)$value;

        if ($isMeter === false) {
            $value *= 1000;
        }

        $value = round($value);

        return (int)$value;
    }

    /**
     * @return int
     */
    public function getDistance(): int
    {
        return $this->distance;
    }

    /**
     * @return string
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->getDistance();
    }
}