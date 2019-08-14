<?php declare(strict_types=1);

namespace Project\Module;

use JsonSerializable;
use Project\Configuration;
use Project\Content;
use Project\Service\Logger;

/**
 * Class DefaultModel
 * @package     Project\Module
 * @copyright   Copyright (c) 2018 Maik Schößler
 */
abstract class DefaultModel implements JsonSerializable
{
    /** @var Configuration $configuration */
    protected $configuration;

    /** @var Content $content */
    protected $content;

    /** @var Logger $logger */
    protected $logger;

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
        $this->configuration = Configuration::getInstance();
        $this->content = Content::getInstance();
        $this->logger = Logger::getInstance();
    }

    /**
     * @return array
     */
    abstract public function toArray(): array;
}