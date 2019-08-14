<?php declare(strict_types=1);

namespace Project\Service;

use InvalidArgumentException;
use Project\Configuration;

/**
 * Class JsPluginService
 * @package Project\Service
 */
class JsPluginService
{
    protected const PACKAGES_NAME = 'js-packages';

    /** @var Configuration $configuration */
    private $configuration;

    /**
     * JsPluginService constructor.
     *
     * @param Configuration $configuration
     */
    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     *
     *
     * @return array
     */
    public function getMainPackages(): array
    {
        $jsMainPackage = [];

        try {
            $jsBox = $this->configuration->getEntryByName('js-boxes');
        } catch (InvalidArgumentException $exception) {
            return $jsMainPackage;
        }

        if (empty($jsBox['main']) === false) {
            foreach ($jsBox['main'] as $package => $enabled) {
                if ($enabled === true) {
                    $jsMainPackage[] = $this->getPackageByPackageName($package);
                }
            }
        }

        return $jsMainPackage;
    }

    /**
     *
     *
     * @param string $routeName
     *
     * @return array
     */
    public function getPackagesByRouteName(string $routeName): array
    {
        $jsRoutePackage = [];

        try {
            $route = $this->configuration->getEntryByName('route')[$routeName];
        } catch (InvalidArgumentException $exception) {
            return $jsRoutePackage;
        }

        if (empty($route) === false && empty($route[self::PACKAGES_NAME]) === false) {
            foreach ($route[self::PACKAGES_NAME] as $routePackage => $enabled) {
                if ($enabled === true) {
                    $jsRoutePackage[] = $this->getPackageByPackageName($routePackage);
                }
            }
        }

        return $jsRoutePackage;
    }

    /**
     *
     *
     * @param string $packageName
     *
     * @return string
     */
    protected function getPackageByPackageName(string $packageName): string
    {
        try {
            $jsPackage = $this->configuration->getEntryByName('js-packages');
        } catch (InvalidArgumentException $exception) {
            return '';
        }

        if (isset($jsPackage[$packageName]) === false) {
            return '';
        }
        return $jsPackage[$packageName];
    }
}