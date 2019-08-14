<?php
declare (strict_types=1);

namespace Project\Controller;

use Project\View\ViewRenderer;

/**
 * Class IndexController
 * @package Project\Controller
 */
class IndexController extends DefaultViewController
{
    /**
     * Startseite
     *
     * Der Nutzer wird auf der Startseite viele verschiedene Übersichten zu sehen bekommen.
     */
    public function indexAction(): void
    {
        $this->viewRenderer->addViewConfig('page', 'home');

        $this->viewRenderer->renderTemplate();
    }

    public function errorAction(): void
    {
        $this->viewRenderer->addViewConfig('page', 'error');
        $this->viewRenderer->renderTemplate(ViewRenderer::PAGE_TEMPLATE);
    }
}