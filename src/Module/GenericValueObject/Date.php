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

    public const YEAR_FORMAT = 'Y';

    public const DAY_MONTH = 'dm';

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
    public function getOutputFormat(): string
    {
        return (string)date(self::DATE_OUTPUT_FORMAT, $this->datetime);
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
     * @return int
     */
    public function getYear(): int
    {
        return (int)date(self::YEAR_FORMAT, $this->datetime);
    }

    /**
     * @param int $days
     * @return bool
     */
    public function isOlderThanDays(int $days): bool
    {
        return ($this->datetime < strtotime('-' . $days . ' days'));
    }

    /**
     * @param Date $date
     *
     * @return bool
     */
    public function isSameOrOlderThan(Date $date): bool
    {
        return ($this->toString() <= $date->toString());
    }

    /**
     * @param Date $date
     *
     * @return bool
     */
    public function isOlderThan(Date $date): bool
    {
        return ($this->toString() < $date->toString());
    }

    /**
     * @return int
     */
    public function getDayAndMonth(): int
    {
        return (int)date(self::DAY_MONTH, $this->datetime);
    }
}