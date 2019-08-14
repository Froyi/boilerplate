<?php
declare (strict_types=1);

namespace Project\Module\GenericValueObject;

use InvalidArgumentException;

/**
 * Class Year
 * @package Project\Module\GenericValueObject
 */
class Year extends DefaultGenericValueObject
{
    /** @var int $year */
    protected $year;

    /**
     * Year constructor.
     *
     * @param int $year
     */
    protected function __construct(int $year)
    {
        $this->year = $year;
    }

    /**
     * @param int $year
     *
     * @return Year
     * @throws InvalidArgumentException
     */
    public static function fromValue($year): self
    {
        self::ensureYearIsValid($year);

        return new self((int)$year);
    }

    /**
     * @param int $year
     *
     * @throws InvalidArgumentException
     */
    protected static function ensureYearIsValid($year): void
    {
        if ($year === false || (int)$year <= 0) {
            throw new InvalidArgumentException('The year is not valid: ' . $year);
        }
    }

    /**
     * @return int
     */
    public function getDiffToToday(): int
    {
        return (int)date('Y') - $this->year;
    }

    /**
     * @return int
     */
    public function getYear(): int
    {
        return $this->year;
    }

    /**
     * @return int
     */
    public function getYearShort(): int
    {
        $yearString = (string)$this->year;

        return (int)substr($yearString, -2);
    }

    /**
     * @return self
     */
    public function getLastYear(): self
    {
        return self::fromValue($this->year - 1);
    }

    /**
     * @return self
     */
    public function getNextYear(): self
    {
        return self::fromValue($this->year + 1);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->getYear();
    }
}

