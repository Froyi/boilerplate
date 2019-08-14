<?php declare (strict_types=1);

namespace Project\Controller;

use Project\Configuration;
use Project\View\JsonModel;

/**
 * Class JsonController
 * @package     Project\Controller
 * @copyright   Copyright (c) 2018 Maik Schößler
 */
class JsonController extends DefaultViewController
{
    /** @var JsonModel $jsonModel */
    protected $jsonModel;

    /**
     * JsonController constructor.
     *
     * @param Configuration $configuration
     * @param string        $routeName
     */
    public function __construct(Configuration $configuration, string $routeName)
    {
        parent::__construct($configuration, $routeName);

        $this->jsonModel = new JsonModel();
    }

    /**
     * Example Action
     *
     * Beispiel, wie ein JSON Response aussehen kann.
     *
     */
    public function exampleAction(): void
    {
        $this->jsonModel->addJsonConfig('test', 'hello');

        $this->jsonModel->send();
    }
}