<?php
declare (strict_types=1);

namespace Project\View;

use Project\Configuration;
use Project\Utilities\Converter;
use Project\Utilities\Tools;
use Project\View\ValueObject\TemplateDir;

/**
 * Class ViewRenderer
 * @package Project\View
 */
class ViewRenderer
{
    protected const DEFAULT_PAGE_TEMPLATE = 'index.twig';

    /** @var TemplateDir $templateDir */
    protected $templateDir;

    /** @var \Twig_Environment $viewRenderer */
    protected $viewRenderer;

    /** @var  string $templateName */
    protected $templateName;

    /** @var  array $config */
    protected $config = [];

    /**
     * ViewRenderer constructor.
     * @param Configuration $configuration
     * @throws \InvalidArgumentException
     */
    public function __construct(Configuration $configuration)
    {
        $template = $configuration->getEntryByName('template');

        $this->templateDir = TemplateDir::fromString($template['dir']);
        $this->templateName = $template['name'];

        $loaderFilesystem = new \Twig_Loader_Filesystem($this->templateDir->getTemplateDir());
        $this->viewRenderer = new \Twig_Environment($loaderFilesystem);

        $this->addViewFilter();

        $templateDir = 'templates/' . $this->templateName;

        $this->addViewConfig('templateDir', $templateDir);
        $this->addViewConfig('mainCssPath', $templateDir . $template['main_css_path']);
    }

    /**
     * @param string $template
     * @throws \Twig_Error_Loader
     * @throws \InvalidArgumentException
     * @throws \Twig_Error_Syntax
     * @throws \Twig_Error_Runtime
     */
    public function renderTemplate(string $template = self::DEFAULT_PAGE_TEMPLATE): void
    {
        echo $this->viewRenderer->render($template, $this->config);
    }

    /**
     * Add filter
     */
    protected function addViewFilter(): void
    {
        $weekDayFilter = new \Twig_SimpleFilter('weekday', function ($integer) {
            return Converter::convertIntToWeekday($integer);
        });

        $this->viewRenderer->addFilter($weekDayFilter);

        $weekDayShortFilter = new \Twig_SimpleFilter('weekdayShort', function ($integer) {
            return Converter::convertIntToWeekdayShort($integer);
        });

        $this->viewRenderer->addFilter($weekDayShortFilter);

        $routeFilter = new \Twig_SimpleFunction('route', function (string $route = '', $parameter = []) {
            return Tools::getRouteUrl($route, $parameter);
        });

        $this->viewRenderer->addFunction($routeFilter);

        $shortenFilter = new \Twig_SimpleFunction('shortener', function (string $text = '', int $amount = 50, bool $points = true) {
            return Tools::shortener($text, $amount, $points);
        });

        $this->viewRenderer->addFunction($shortenFilter);
    }

    /**
     * @param string $name
     * @param        $value
     */
    public function addViewConfig(string $name, $value): void
    {
        $this->config[$name] = $value;
    }
}