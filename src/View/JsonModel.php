<?php declare (strict_types=1);

namespace Project\View;

/**
 * Class JsonModel
 * @package     Project\View
 * @copyright   Copyright (c) 2018 Maik Schößler
 */
class JsonModel
{
    /** @var array $jsonConfig */
    protected $jsonConfig = [];

    /**
     * JsonModel constructor.
     */
    public function __construct()
    {
        $this->initJsonConfig();
    }

    /**
     * initial config for json encode
     */
    protected function initJsonConfig(): void
    {
        $this->jsonConfig['status'] = 'error';
    }

    /**
     * @param string $name
     * @param        $entry
     */
    public function addJsonConfig(string $name, $entry): void
    {
        $this->jsonConfig[$name] = $entry;
    }

    /**
     * @param string $status
     */
    public function send(string $status = 'success'): void
    {
        $this->jsonConfig['status'] = $status;

        echo json_encode($this->jsonConfig);
        exit;
    }
}