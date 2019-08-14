<?php
declare (strict_types=1);

namespace Project\Module\GenericValueObject;

use InvalidArgumentException;
use function strlen;

/**
 * Class Age
 * @package Project\Module\GenericValueObject
 */
class Age extends DefaultGenericValueObject
{
    protected const MAX_LENGTH_AGE = 4;

    /** @var int $age */
    protected $age;

    /**
     * Age constructor.
     *
     * @param int $age
     */
    protected function __construct(int $age)
    {
        $this->age = $age;
    }

    /**
     * @param int $age
     *
     * @return Age
     * @throws InvalidArgumentException
     */
    public static function fromValue($age): self
    {
        self::ensureAgeIsValid($age);

        $age = self::convertAge($age);

        return new self($age);
    }

    /**
     * @param BirthYear $birthYear
     *
     * @return Age
     * @throws InvalidArgumentException
     */
    public static function getAgeByBirthYear(BirthYear $birthYear): self
    {
        return self::fromValue($birthYear->getAge());
    }

    /**
     * @param $age
     *
     * @throws InvalidArgumentException
     */
    protected static function ensureAgeIsValid($age): void
    {
        $age = (string)$age;

        if (strlen($age) > self::MAX_LENGTH_AGE) {
            throw new InvalidArgumentException('The age is not valid: ' . $age);
        }
        if ((int)$age < 0) {
            throw new InvalidArgumentException('The age is not valid: ' . $age);
        }
    }

    /**
     * @param $age
     *
     * @return int
     */
    protected static function convertAge($age): int
    {
        return (int)$age;
    }

    /**
     * @return int
     */
    public function getAge(): int
    {
        return $this->age;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->getAge();
    }
}

