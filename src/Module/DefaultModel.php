<?php declare(strict_types=1);

namespace Project\Module;

/**
 * Class DefaultModel
 * @package     Project\Module
 * @copyright   Copyright (c) 2018 Maik Schößler
 */
class DefaultModel implements \JsonSerializable
{
    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}