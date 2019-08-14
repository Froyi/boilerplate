<?php declare (strict_types=1);

namespace Project;

use InvalidArgumentException;

/**
 * Class Configuration
 * @package Project
 */
class Configuration
{
    protected const DEFAULT_CONFIG_PATH = ROOT_PATH . '/config.php';

    protected const ROUTE_CONFIG_PATH = ROOT_PATH . '/config-routing.php';

    protected const JS_CONFIG_PATH = ROOT_PATH . '/config-js.php';

    protected const ENV_CONFIG_PATH = ROOT_PATH . '/environment.php';

    protected const CONFIG_LIST = [
        'default' => self::DEFAULT_CONFIG_PATH,
        'route' => self::ROUTE_CONFIG_PATH,
        'js' => self::JS_CONFIG_PATH,
        'env' => self::ENV_CONFIG_PATH
    ];

    /** @var array $configuration */
    protected $configuration;

    /** @var null|self $instance */
    public static $instance;

    /**
     * @return Configuration
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Configuration constructor.
     */
    public function __construct()
    {
        $this->configuration = [];

        foreach(self::CONFIG_LIST as $config) {
            /** @noinspection SlowArrayOperationsInLoopInspection */
            /** @noinspection PhpIncludeInspection */
            $this->configuration = array_merge($this->configuration, include $config);
        }
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
     *
     * @return mixed
     */
    public function getEntryByName(string $name)
    {
        if (!isset($this->configuration[$name])) {
            throw new InvalidArgumentException('there has to be a valid config key');
        }

        return $this->configuration[$name];
    }
}