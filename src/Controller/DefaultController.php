<?php
declare (strict_types=1);

namespace Project\Controller;

use Project\Configuration;
use Project\Module\Database\Database;
use Project\Service\JsPluginService;
use Project\View\ViewRenderer;

/**
 * Class DefaultController
 * @package Project\Controller
 */
class DefaultController
{
    /** @var ViewRenderer $viewRenderer */
    protected $viewRenderer;

    /** @var Configuration $configuration */
    protected $configuration;

    /** @var Database $database */
    protected $database;

    /**
     * DefaultController constructor.
     *
     * @param Configuration $configuration
     * @param string        $routeName
     */
    public function __construct(Configuration $configuration, string $routeName)
    {
        $this->configuration = $configuration;
        $this->viewRenderer = new ViewRenderer($this->configuration);
        $this->database = new Database($this->configuration);

        $this->setDefaultViewConfig();

        $this->setJsPackages($routeName);
    }

    /**
     * Sets default view parameter for sidebar etc.
     */
    protected function setDefaultViewConfig(): void
    {
        $this->viewRenderer->addViewConfig('page', 'notfound');
    }

    /**
     * @param string $routeName
     */
    protected function setJsPackages(string $routeName): void
    {
        $jsPlugInService = new JsPluginService($this->configuration);

        $jsMainPackage = $jsPlugInService->getMainPackages();
        $this->viewRenderer->addViewConfig('jsPlugins', $jsMainPackage);

        $jsRoutePackage = $jsPlugInService->getPackagesByRouteName($routeName);
        $this->viewRenderer->addViewConfig('jsRoutePlugins', $jsRoutePackage);
    }

    /**
     * not found action
     * @throws \Twig_Error_Syntax
     * @throws \InvalidArgumentException
     * @throws \Twig_Error_Runtime
     */
    public function notFoundAction(): void
    {
        try {
            $this->viewRenderer->addViewConfig('page', 'notfound');

            $this->viewRenderer->renderTemplate();
        } catch (\Twig_Error_Loader $error) {
            echo 'Alles ist kaputt!';
        }
    }

    /**
     * error action
     * @throws \Twig_Error_Runtime
     * @throws \InvalidArgumentException
     * @throws \Twig_Error_Syntax
     * @throws \Twig_Error_Loader
     */
    public function errorPageAction(): void
    {
        $this->showStandardPage('error');
    }

    /**
     * @param string $name
     * @throws \InvalidArgumentException
     * @throws \Twig_Error_Syntax
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Loader
     */
    protected function showStandardPage(string $name): void
    {
        try {
            $this->viewRenderer->addViewConfig('page', $name);

            $this->viewRenderer->renderTemplate();
        } catch (\InvalidArgumentException $error) {
            $this->notFoundAction();
        }
    }
}