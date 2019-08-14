<?php
declare (strict_types=1);

namespace Project\Module\GenericValueObject;

/**
 * Class Datetime
 * @package Project\Module\GenericValueObject
 */
class Datetime extends AbstractDatetime implements DatetimeInterface
{
    public const DATETIME_FORMAT = 'Y-m-d H:i';

    public const FORM_FORMAT = 'Y-m-d\TH:i:s';

    public const DB_FORMAT = 'Y-m-d H:i:s';

    public const DATE_FORMAT = 'Y-m-d';

    public const DATETIME_OUTPUT_FORMAT = 'd.m.Y H:i';

    public const DATE_OUTPUT_FORMAT = 'd.m.Y';

    public const TIME_FORMAT = 'H:i';

    public const WEEKDAY_FORMAT = 'w';

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)date(self::DATETIME_OUTPUT_FORMAT, $this->datetime);
    }

    /**
     * @return string
     */
    public function getDatetimeOutput(): string
    {
        return (string)date(self::DATETIME_OUTPUT_FORMAT, $this->datetime);
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return (string)date(self::DATETIME_FORMAT, $this->datetime);
    }

    /**
     * @return string
     */
    public function getFormFormat(): string
    {
        return (string)date(self::FORM_FORMAT, $this->datetime);
    }

    /**
     * @return string
     */
    public function getDbFormat(): string
    {
        return (string)date(self::DB_FORMAT, $this->datetime);
    }

    /**
     * @return int
     */
    public function getWeekday(): int
    {
        return (int)date(self::WEEKDAY_FORMAT, $this->datetime);
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return (string)date(self::DATE_FORMAT, $this->datetime);
    }

    /**
     * @return string
     */
    public function getDateString(): string
    {
        return (string)date(self::DATE_OUTPUT_FORMAT, $this->datetime);
    }

    /**
     * @return string
     */
    public function getTimeString(): string
    {
        return (string)date(self::TIME_FORMAT, $this->datetime);
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
     * @param DatetimeInterface $datetime
     *
     * @return \DateTime|false|int
     */
    public function getDifference(DatetimeInterface $datetime)
    {
        $date = strtotime($datetime->getDbFormat());

        return $this->datetime - $date;
    }

    /**
     * @param int $seconds
     *
     * @return Datetime
     */
    public function addSeconds(int $seconds): self
    {
        return new self($this->datetime + $seconds);
    }
}