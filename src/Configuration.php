<?php declare (strict_types=1);

namespace Project;

/**
 * Class Configuration
 * @package Project
 */
class Configuration
{
    protected const DEFAULT_CONFIG_PATH = ROOT_PATH . '/config.php';

    protected const ROUTE_CONFIG_PATH = ROOT_PATH . '/config-routing.php';

    protected const JS_CONFIG_PATH = ROOT_PATH . '/config-js.php';

    protected const CONFIG_LIST = [
        'default' => self::DEFAULT_CONFIG_PATH,
        'route' => self::ROUTE_CONFIG_PATH,
        'js' => self::JS_CONFIG_PATH
    ];


    /** @var array $configuration */
    protected $configuration;

    /**
     * Configuration constructor.
     */
    public function __construct()
    {
        $this->configuration = [];

        foreach(self::CONFIG_LIST as $config) {
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