<?php declare(strict_types=1);

namespace Project\Module\GenericValueObject;

use InvalidArgumentException;
use function is_int;
use function is_string;

/**
 * Class StartNumber
 * @package     Project\Module\Runner
 */
class StartNumber extends DefaultGenericValueObject
{
    /** @var int $startNumber */
    protected $startNumber;

    /**
     * StartNumber constructor.
     *
     * @param int $startNumber
     */
    protected function __construct(int $startNumber)
    {
        $this->startNumber = $startNumber;
    }

    /**
     * @param $startNumber
     *
     * @return StartNumber
     * @throws InvalidArgumentException
     */
    public static function fromValue($startNumber): self
    {
        self::ensureStartNumberIsValid($startNumber);

        return new self((int)$startNumber);
    }

    /**
     * @param $startNumber
     *
     * @throws InvalidArgumentException
     */
    protected static function ensureStartNumberIsValid($startNumber): void
    {
        if ((is_int($startNumber) === false && is_string($startNumber) === false) || (int)$startNumber < 0) {
            throw new InvalidArgumentException('This Number is not valid: ' . $startNumber);
        }
    }

    /**
     * @return int
     */
    public function getStartNumber(): int
    {
        return $this->startNumber;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->getStartNumber();
    }

    /**
     * @param StartNumber $startNumber
     *
     * @return bool
     */
    public function eval(StartNumber $startNumber): bool
    {
        return ($this->startNumber === $startNumber->getStartNumber());
    }
}