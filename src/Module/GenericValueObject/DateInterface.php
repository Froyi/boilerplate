<?php
declare (strict_types=1);

namespace Project\Module\GenericValueObject;

/**
 * Interface DateInterface
 * @package Project\Module\GenericValueObject
 */
interface DateInterface
{
    /**
     * @return int
     */
    public function getWeekday(): int;

    /**
     * @param int $days
     * @return bool
     */
    public function isOlderThanDays(int $days): bool;

    /**
     * @return string
     */
    public function toString(): string;
}