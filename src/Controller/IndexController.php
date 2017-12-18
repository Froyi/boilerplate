<?php
declare (strict_types=1);

namespace Project\Controller;

/**
 * Class IndexController
 * @package Project\Controller
 */
class IndexController extends DefaultController
{
    /**
     * index action (standard page)
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Loader
     * @throws \InvalidArgumentException
     * @throws \Twig_Error_Syntax
     */
    public function indexAction(): void
    {
        $this->showStandardPage('home');
    }

    /**
     * another example index action
     * @throws \Twig_Error_Runtime
     * @throws \InvalidArgumentException
     * @throws \Twig_Error_Syntax
     */
    public function differentIndexAcion(): void
    {
        try {
            $this->viewRenderer->addViewConfig('slider', 'sliderVariable');
            $this->viewRenderer->addViewConfig('page', 'home');

            $this->viewRenderer->renderTemplate();
        } catch (\Twig_Error_Loader $error) {
            $this->notFoundAction();
        }
    }
}