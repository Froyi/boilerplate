<?php
declare(strict_types=1);

namespace Project\Module;

use Project\Configuration;
use Project\Content;
use Project\Service\Logger;

/**
 * Class DefaultFactory
 * @package     Project\Module
 */
class DefaultFactory
{
    /** @var Configuration $configuration */
    protected $configuration;

    /** @var Content $content */
    protected $content;

    /** @var Logger $logger */
    protected $logger;

    /**
     * RunnerRepository constructor.
     */
    public function __construct()
    {
        $this->configuration = Configuration::getInstance();
        $this->content = Content::getInstance();
        $this->logger = Logger::getInstance();
    }
}