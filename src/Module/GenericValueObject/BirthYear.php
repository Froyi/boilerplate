<?php
declare (strict_types=1);

namespace Project\Module\GenericValueObject;

use InvalidArgumentException;

/**
 * Class BirthYear
 * @package Project\Module\GenericValueObject
 */
class BirthYear extends DefaultGenericValueObject
{
    /** @var int $birthYear */
    protected $birthYear;

    /**
     * BirthYear constructor.
     *
     * @param int $birthYear
     */
    protected function __construct(int $birthYear)
    {
        $this->birthYear = $birthYear;
    }

    /**
     * @param int $birthYear
     *
     * @return BirthYear
     */
    public static function fromValue(int $birthYear): self
    {
        self::ensureBirthYearIsValid($birthYear);

        return new self($birthYear);
    }

    /**
     * @param int $birthYear
     *
     */
    protected static function ensureBirthYearIsValid(int $birthYear): void
    {
        if ($birthYear < 0 || $birthYear > date('Y')) {
            throw new InvalidArgumentException('The birthYear is not valid: ' . $birthYear);
        }
    }

    /**
     * @return int
     */
    public function getAge(): int
    {
        return (int)date('Y') - $this->birthYear;
    }

    /**
     * @return int
     */
    public function getBirthYear(): int
    {
        return $this->birthYear;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->getBirthYear();
    }

    /**
     * @return string
     */
    public function getShortBirthYear(): string
    {
        $birthYear = (string)$this->getBirthYear();
        return substr($birthYear, -2);
    }
}

