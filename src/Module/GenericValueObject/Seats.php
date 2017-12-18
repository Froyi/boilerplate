<?php
declare (strict_types=1);

namespace Project\Module\GenericValueObject;

/**
 * Class Seats
 * @package Project\Module\GenericValueObject
 */
class Seats
{
    const SEATS_MIN = 1;

    /** @var int $seats */
    protected $seats;

    /**
     * Seats constructor.
     * @param int $seats
     */
    protected function __construct(int $seats)
    {
        $this->seats = $seats;
    }

    /**
     * @param int $seats
     * @return Seats
     * @throws \InvalidArgumentException
     */
    public static function fromValue(int $seats): self
    {
        self::ensureSeatsIsValid($seats);

        return new self($seats);
    }

    /**
     * @param $seats
     * @throws \InvalidArgumentException
     */
    protected static function ensureSeatsIsValid($seats): void
    {
        if ($seats < self::SEATS_MIN) {
            throw new \InvalidArgumentException('Too few seats chosen.', 1);
        }
    }

    /**
     * @return int
     */
    public function getSeats(): int
    {
        return $this->seats;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->seats;
    }
}
