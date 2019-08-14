<?php
declare (strict_types=1);

namespace Project\Controller;

use Project\Configuration;
use Project\View\ViewRenderer;

/**
 * Class AdminController
 * @package Project\Controller
 */
class AdminController extends DefaultViewController
{
    /**
     * AdminController constructor.
     *
     * @param Configuration $configuration
     * @param string        $routeName
     */
    public function __construct(Configuration $configuration, string $routeName)
    {
        parent::__construct($configuration, $routeName);

        if ($this->loggedInUser === null) {
            $this->errorRouting('login');
            exit;
        }

        $this->viewRenderer->addViewConfig('isAdmin', 'true');
    }

    /**
     * Admin Startseite
     */
    public function adminAction(): void
    {
        $this->viewRenderer->addViewConfig('page', 'admin');

        $this->viewRenderer->renderTemplate(ViewRenderer::ADMIN_TEMPLATE);
    }
}