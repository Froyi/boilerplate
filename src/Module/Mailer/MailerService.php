<?php declare(strict_types=1);

namespace Project\Module\Mailer;

use Project\Configuration;
use Project\Module\GenericValueObject\Email;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

/**
 * Class MailerService
 * @package     Project\Module\Mailer
 * @copyright   Copyright (c) 2018 Maik Schößler
 */
class MailerService
{
    /** @var string MAILER_CONFIG_KEY */
    protected const MAILER_CONFIG_KEY = 'mailer';

    /** @var array $mailerConfiguration */
    protected $mailerConfiguration;

    /** @var Swift_SmtpTransport $transport */
    protected $transport;

    /** @var Swift_Mailer $mailer */
    protected $mailer;

    /** @var array $errors */
    protected $errors;

    /** @var \Swift_Plugins_Loggers_ArrayLogger $logger */
    protected $logger;

    /**
     * MailerService constructor.
     *
     * @param Configuration $configuration
     * @param bool          $logger
     *
     * @throws \Exception
     */
    public function __construct(Configuration $configuration, bool $logger = false)
    {
        $mailConfiguration = $configuration->getEntryByName(self::MAILER_CONFIG_KEY);

        if ($this->validateMailerConfig($mailConfiguration) === true) {
            $this->mailerConfiguration = $mailConfiguration;

            // Create the Transport
            $this->transport = (new Swift_SmtpTransport($this->mailerConfiguration['server'], $this->mailerConfiguration['port']))->setUsername($this->mailerConfiguration['user'])->setPassword($this->mailerConfiguration['password']);
            // Create the Mailer using your created Transport
            $this->mailer = new Swift_Mailer($this->transport);
        } else {
            throw new \InvalidArgumentException('Mailer could not be initialized.');
        }

        if ($logger === true) {
            $this->registerLogger();
        }
    }

    /**
     * @param Email $to
     * @param       $subject
     * @param       $message
     *
     * @return bool
     */
    public function sendSingleStandardMail(Email $to, $subject, $message): bool
    {
        // Create a message
        $message = $this->buildMessage($to, $subject, $message);

        if ($this->mailer->send($message, $this->errors) === false) {
            if ($this->logger !== null) {
                echo "Error:" . $this->logger->dump();
            }

            return false;
        } else {
            if ($this->logger !== null) {
                echo "Success";
            }
        }

        return true;
    }

    /**
     * register logger for debugging
     */
    protected function registerLogger()
    {
        $this->logger = new \Swift_Plugins_Loggers_ArrayLogger();
        $this->mailer->registerPlugin(new \Swift_Plugins_LoggerPlugin($this->logger));
    }

    /**
     * @param Email $to
     * @param       $subject
     * @param       $message
     *
     * @return Swift_Message
     */
    protected function buildMessage(Email $to, $subject, $message): Swift_Message
    {
        $standardMailName = null;
        if (empty($this->mailerConfiguration['standard_from_name']) === false) {
            $standardMailName = $this->mailerConfiguration['standard_from_name'];
        }

        $swiftMessage = new Swift_Message($subject);

        $swiftMessage->setFrom([$this->mailerConfiguration['standard_from_mail'] => $standardMailName]);
        $swiftMessage->setTo($to->getEmail());
        $swiftMessage->setBody($message);

        return $swiftMessage;
    }

    /**
     * @param array $mailConfiguration
     *
     * @return bool
     */
    protected function validateMailerConfig(array $mailConfiguration): bool
    {
        if (empty($mailConfiguration['server']) === true || empty($mailConfiguration['port']) === true) {
            return false;
        }

        if (empty($mailConfiguration['user']) === true || empty($mailConfiguration['password']) === true) {
            return false;
        }

        return true;
    }
}