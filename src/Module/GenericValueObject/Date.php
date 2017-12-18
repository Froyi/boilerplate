<?php
declare (strict_types = 1);

namespace Project\Module\GenericValueObject;

/**
 * Class Date
 * @package Project\Module\GenericValueObject
 */
class Date extends AbstractDatetime implements DateInterface
{
    public const DATE_FORMAT = 'Y-m-d';

    public const DATE_OUTPUT_FORMAT = 'd.m.Y';

    public const WEEKDAY_FORMAT = 'w';

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string) date(self::DATE_OUTPUT_FORMAT, $this->datetime);
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return (string) date(self::DATE_FORMAT, $this->datetime);
    }

    /**
     * @return int
     */
    public function getWeekday(): int
    {
        return (int) date(self::WEEKDAY_FORMAT, $this->datetime);
    }

    /**
     * @param $datetime
     * @return AbstractDatetime
     * @throws \InvalidArgumentException
     */
    public static function fromValue($datetime): AbstractDatetime
    {
        return parent::fromValue($datetime);
    }

    /**
     * @param int $days
     * @return bool
     */
    public function isOlderThanDays(int $days): bool
    {
        return ($this->datetime < strtotime('-' . $days . ' days'));
    }
}