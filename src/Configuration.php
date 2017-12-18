<?php
declare (strict_types=1);

namespace Project;

/**
 * Class Configuration
 * @package Project
 */
class Configuration
{
    protected const CONFIG_PATH = ROOT_PATH . '/config.php';

    /** @var array $configuration */
    protected $configuration;

    /**
     * Configuration constructor.
     */
    public function __construct()
    {
        $this->configuration = include self::CONFIG_PATH;
    }

    /**
     * @return array
     */
    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    /**
     * @param string $name
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function getEntryByName(string $name)
    {
        if (!isset($this->configuration[$name])) {
            throw new \InvalidArgumentException('there has to be a valid config key');
        }

        return $this->configuration[$name];
    }
}