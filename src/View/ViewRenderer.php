<?php
declare (strict_types=1);

namespace Project\View;

use InvalidArgumentException;
use Project\Configuration;
use Project\Content;
use Project\Utilities\Converter;
use Project\Utilities\Tools;
use Project\View\ValueObject\TemplateDir;
use Twig\Environment;
use Twig\Error\Error;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * Class ViewRenderer
 * @package Project\View
 */
class ViewRenderer
{
    protected const DEFAULT_PAGE_TEMPLATE = 'index.twig';
    public const PAGE_TEMPLATE = 'page.twig';
    public const ADMIN_TEMPLATE = 'admin.twig';
    public const BLANK_TEMPLATE = 'blank.twig';

    /** @var TemplateDir $templateDir */
    protected $templateDir;

    /** @var Environment $viewRenderer */
    protected $viewRenderer;

    /** @var  string $templateName */
    protected $templateName;

    /** @var  array $config */
    protected $config = [];

    /**
     * ViewRenderer constructor.
     * @param Configuration $configuration
     */
    public function __construct(Configuration $configuration)
    {
        try {
            $template = $configuration->getEntryByName('template');

            $this->templateDir = TemplateDir::fromString($template['dir']);
            $this->templateName = $template['name'];
        } catch (InvalidArgumentException $exception) {
            echo 'We do not have any Template!';
            exit;
        }

        $loaderFilesystem = new FilesystemLoader($this->templateDir->getTemplateDir());
        $this->viewRenderer = new Environment($loaderFilesystem);

        $this->addViewFilter();

        $templateDir = 'templates/' . $this->templateName;

        $this->addViewConfig('templateDir', $templateDir);
        $this->addViewConfig('mainCssPath', $templateDir . $template['main_css_path']);
    }

    /**
     * @param string $template
     *
     */
    public function renderTemplate(string $template = self::DEFAULT_PAGE_TEMPLATE): void
    {
        try {
            echo $this->viewRenderer->render($template, $this->config);
        } catch (Error | InvalidArgumentException | SyntaxError | RuntimeError $exception) {
            echo $exception->getMessage();
            exit;
        }
    }

    /**
     * @param string $template
     *
     * @return string
     */
    public function renderJsonView(string $template = self::DEFAULT_PAGE_TEMPLATE): string
    {
        try {
            return $this->viewRenderer->render($template, $this->config);
        } catch (Error | InvalidArgumentException | SyntaxError | RuntimeError $exception) {
            return '';
        }
    }

    /**
     * @param string $name
     * @param        $value
     */
    public function addViewConfig(string $name, $value): void
    {
        $this->config[$name] = $value;
    }

    /**
     * @param string $name
     */
    public function removeViewConfig(string $name): void
    {
        if (isset($this->config[$name])) {
            unset($this->config[$name]);
        }
    }

    /**
     * Add filter
     */
    protected function addViewFilter(): void
    {
        $weekDayFilter = new TwigFilter('weekday', static function ($integer) {
            return Converter::convertIntToWeekday($integer);
        });

        $this->viewRenderer->addFilter($weekDayFilter);

        $weekDayShortFilter = new TwigFilter('weekdayShort', static function ($integer) {
            return Converter::convertIntToWeekdayShort($integer);
        });

        $this->viewRenderer->addFilter($weekDayShortFilter);

        $routeFilter = new TwigFunction('route', static function (string $route = '', $parameter = []) {
            return Tools::getRouteUrl($route, $parameter);
        });

        $this->viewRenderer->addFunction($routeFilter);

        $contentFilter = new TwigFunction('content', static function (string $name = '', ?array $parameter = []) {
            return Content::getContent($name, $parameter);
        });

        $this->viewRenderer->addFunction($contentFilter);

        $shortenFilter = new TwigFunction('shortener', static function (string $text = '', int $amount = 50, bool $points = true) {
            return Tools::shortener($text, $amount, $points);
        });

        $this->viewRenderer->addFunction($shortenFilter);

        $convertDiffFilter = new TwigFilter('convertDiff', static function ($integer) {
            return Converter::convertDiff($integer);
        });

        $this->viewRenderer->addFilter($convertDiffFilter);
    }
}