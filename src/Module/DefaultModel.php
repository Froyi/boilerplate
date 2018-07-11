<?php declare(strict_types=1);

namespace Project\Module;

use Project\Configuration;

/**
 * Class DefaultModel
 * @package     Project\Module
 * @copyright   Copyright (c) 2018 Maik Schößler
 */
class DefaultModel implements \JsonSerializable
{
    /** @var Configuration $configuration */
    protected $configuration;

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    /**
     * DefaultModel constructor.
     */
    public function __construct()
    {
        $this->configuration = new Configuration();
    }
}