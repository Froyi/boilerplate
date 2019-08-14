<?php
declare (strict_types=1);

namespace Project\Controller;

use InvalidArgumentException;
use Project\Configuration;
use Project\Service\JsPluginService;
use Project\View\ViewRenderer;

/**
 * Class DefaultViewController
 * @package Project\Controller
 */
class DefaultViewController extends DefaultController
{
    /** @var string LOGO_MAIN_NAME */
    protected const LOGO_MAIN_NAME = 'logoMain';

    /** @var ViewRenderer $viewRenderer */
    protected $viewRenderer;

    /**
     * DefaultController constructor.
     *
     * @param Configuration $configuration
     * @param string        $routeName
     */
    public function __construct(Configuration $configuration, string $routeName)
    {
        parent::__construct($configuration);

        $this->viewRenderer = new ViewRenderer($this->configuration);

        $this->setDefaultViewConfig($routeName);

        $this->setJsPackages($routeName);
    }

    /**
     * not found action
     */
    public function notFoundAction(): void
    {
        $this->viewRenderer->addViewConfig('page', 'notfound');

        $this->viewRenderer->renderTemplate();
    }

    /**
     * Sets default view parameter for sidebar etc.
     *
     * @param string $routeName
     *
     */
    protected function setDefaultViewConfig(string $routeName): void
    {
        $this->viewRenderer->addViewConfig('page', 'notfound');

        /**
         * Environment
         */
        $this->viewRenderer->addViewConfig('environment', ENVIRONMENT);

        /**
         * Notifications
         */
        $this->viewRenderer->addViewConfig('notifications', $this->notificationService->getNotifications());

        /**
         * today
         */
        try {
            $this->viewRenderer->addViewConfig('today', $this->today->toString());
        } catch (InvalidArgumentException $exception) {
            $this->logger->addNotice('today konnte nicht definiert werden.');
        }

        /**
         * Logo
         */
        $this->viewRenderer->addViewConfig('logo', $this->getLogoByRoute($routeName));
        try {
            $this->viewRenderer->addViewConfig('logoPermanentStarter', $this->configuration->getEntryByName('logoPermanentStarter'));
        } catch (InvalidArgumentException $exception) {
            $this->logger->addCritical('Das Logo ist nicht mehr vorhanden.');
        }
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
     * @param string $name
     */
    protected function showStandardPage(string $name): void
    {
        try {
            $this->viewRenderer->addViewConfig('page', $name);

            $this->viewRenderer->renderTemplate();
        } catch (InvalidArgumentException $error) {
            $this->logger->addError('Diese Seite konnte nicht gefunden werden.');
            $this->notFoundAction();
        }
    }

    /**
     * @param string $routeName
     *
     * @return string
     */
    protected function getLogoByRoute(string $routeName): string
    {
        $logoName = self::LOGO_MAIN_NAME;

        try {
            $logoRoutes = $this->configuration->getEntryByName('logoRoutes');

            if (isset($logoRoutes[$routeName]) === true) {
                $logoName = $logoRoutes[$routeName];
            }

            return $this->configuration->getEntryByName($logoName);
        } catch (InvalidArgumentException $exception) {
            $this->logger->addCritical('Es konnte kein Logo in der Config gefunden werden.');
            return '';
        }
    }
}