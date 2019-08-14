<?php
declare(strict_types=1);

namespace Project\Module;

use Project\Configuration;
use Project\Content;
use Project\Module\Database\Database;
use Project\Service\Logger;

/**
 * Class DefaultRepository
 * @package Project\Module
 */
class DefaultRepository
{
    /** @var Database $database */
    protected $database;

    /** @var Configuration $configuration */
    protected $configuration;

    /** @var Content $content */
    protected $content;

    /** @var Logger $logger */
    protected $logger;

    /**
     * RunnerRepository constructor.
     *
     * @param Database $database
     */
    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->configuration = Configuration::getInstance();
        $this->content = Content::getInstance();
        $this->logger = Logger::getInstance();
    }
}