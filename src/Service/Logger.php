<?php
declare(strict_types=1);

namespace Project\Service;

use Exception;
use Monolog\Formatter\HtmlFormatter;
use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\FirePHPHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SwiftMailerHandler;
use Monolog\Logger as MonoLogger;
use Project\Configuration;
use Project\Content;
use Project\Module\Mailer\MailerService;
use Swift_Message;

/**
 * Class Logger
 * @package     Project\Service
 */
class Logger
{
    /** @var MonoLogger $logger */
    public $logger;

    /** @var null|self $instance */
    public static $instance;

    /**
     * @return Logger
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self(Configuration::getInstance(), Content::getInstance());
        }

        return self::$instance;
    }

    /**
     * Logger constructor.
     *
     * @param Configuration $configuration
     * @param Content       $content
     */
    public function __construct(Configuration $configuration, Content $content)
    {
        $mailerService = new MailerService($configuration, $content, false);
        $mailConfig = $configuration->getEntryByName('mailer');

        $mailer = $mailerService->getMailer();

        // Create a message
        $message = new Swift_Message('Probleme auf der Heidelaufseite');
        $message->setFrom([$mailConfig['standard_from_mail'] => 'Heidelaufwebseite']);
        $message->setTo([$mailConfig['logger_send_adress'] => 'Administrator']);
        $message->setContentType('text/html');

        $mailerHandler = new SwiftMailerHandler($mailer, $message, MonoLogger::CRITICAL);
        $mailerHandler->setFormatter(new HtmlFormatter());

        $this->logger = new MonoLogger('default');

        try {
            $streamHandler = new StreamHandler('error.log', MonoLogger::DEBUG);
            $streamHandler->setFormatter(new JsonFormatter());

            $this->logger->pushHandler($streamHandler);
        } catch (Exception $exception) {
            // do nothing
        }

        $firePHPHandler = new FirePHPHandler();
        $firePHPHandler->setFormatter(new JsonFormatter());
        
        $this->logger->pushHandler($firePHPHandler);

        if (ENVIRONMENT === 'production' || ENVIRONMENT === 'testing') {
            $this->logger->pushHandler($mailerHandler);
        }
    }

    /**
     * @param $message
     */
    public function addCritical($message): void
    {
        $this->logger->addCritical($message);
    }

    /**
     * @param $message
     */
    public function addError($message): void
    {
        $this->logger->addError($message);
    }

    /**
     * @param $message
     */
    public function addNotice($message): void
    {
        $this->logger->addNotice($message);
    }

    /**
     * @param $message
     */
    public function addWarning($message): void
    {
        $this->logger->addWarning($message);
    }
}