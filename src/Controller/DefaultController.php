<?php
declare (strict_types=1);

namespace Project\Controller;

use InvalidArgumentException;
use Project\Configuration;
use Project\Content;
use Project\Module\Database\Database;
use Project\Module\GenericValueObject\Date;
use Project\Module\GenericValueObject\Id;
use Project\Module\Notification\NotificationService;
use Project\Module\Pdf\PdfService;
use Project\Module\User\User;
use Project\Module\User\UserService;
use Project\Service\Logger;
use Project\Utilities\Tools;

/**
 * Class DefaultController
 * @package Project\Controller
 */
class DefaultController
{
    /** @var string LOGO_MAIN_NAME */
    protected const LOGO_MAIN_NAME = 'logoMain';

    /** @var Configuration $configuration */
    protected $configuration;

    /** @var Content $content */
    protected $content;

    /** @var NotificationService $notificationService */
    protected $notificationService;

    /** @var Database $database */
    protected $database;

    /** @var Date $today */
    protected $today;

    /** @var UserService $userService */
    protected $userService;

    /** @var null|User $loggedInUser */
    protected $loggedInUser;

    /** @var Logger $logger */
    protected $logger;

    /** @var PdfService $pdfService */
    protected $pdfService;

    /**
     * DefaultController constructor.
     *
     * @param Configuration $configuration
     */
    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
        $this->content = Content::getInstance();

        $this->logger = Logger::getInstance();

        try {
            $this->database = new Database($this->configuration);
        } catch (InvalidArgumentException $exception) {
            $this->logger->addCritical('Die Datenbank ist weg!!!');
            echo 'No connection to Database!';
            exit;
        }

        $this->today = Date::fromValue('today');

        $this->userService = new UserService($this->database);
        $this->notificationService = new NotificationService();

        /*try {
            if (Tools::getValue('userId') !== false) {
                $userId = Id::fromString(Tools::getValue('userId'));
                $this->loggedInUser = $this->userService->getLoggedInUserByUserId($userId);
            }
        } catch (InvalidArgumentException $exception) {
            $this->logger->addNotice('Es wurde versucht einen Nutzer mit ungültiger UserId anzumelden. UserId: ' . Tools::getValue('userId'));
        }*/

        $this->pdfService = new PdfService();

        $this->userService = new UserService($this->database);
    }

    /**
     * @param string $routeName
     * @param string $message
     */
    protected function errorRouting(string $routeName, string $message = null): void
    {
        if ($message !== null) {
            $this->notificationService->setError($message);
        }

        header('Location: ' . Tools::getRouteUrl($routeName));
        exit;
    }

    /**
     * @param string      $routeName
     * @param string|null $message
     * @param array       $parameter
     */
    protected function successRouting(string $routeName, string $message = null, array $parameter = []): void
    {
        if ($message !== null) {
            $this->notificationService->setSuccess($message);
        }

        header('Location: ' . Tools::getRouteUrl($routeName, $parameter));
        exit;
    }

    /**
     * @param string $routeName
     * @param array  $parameter
     */
    protected function redirect(string $routeName, array $parameter = []): void
    {
        header('Location: ' . Tools::getRouteUrl($routeName, $parameter));
        exit;
    }
}