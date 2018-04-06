<?php declare(strict_types=1);

namespace Project\Module\GenericValueObject;

/**
 * Class DefaultGenericValueObject
 * @package     Project\Module\GenericValueObject
 * @copyright   Copyright (c) 2018 Maik Schößler
 */
class DefaultGenericValueObject implements \JsonSerializable
{
    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}