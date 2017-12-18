<?php
declare (strict_types=1);

namespace Project\Module\GenericValueObject;

/**
 * Interface DatetimeInterface
 * @package Project\Module\GenericValueObject
 */
interface DatetimeInterface extends DateInterface
{
    /**
     * @return string
     */
    public function toString(): string;

    /**
     * @return string
     */
    public function getDateString(): string;

    /**
     * @return string
     */
    public function getDate(): string;

    /**
     * @return string
     */
    public function getTimeString(): string;
}