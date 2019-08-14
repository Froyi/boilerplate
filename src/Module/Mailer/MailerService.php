<?php declare(strict_types=1);

namespace Project\Module\Mailer;

use InvalidArgumentException;
use Project\Configuration;
use Project\Content;
use Project\Module\GenericValueObject\Email;
use Project\Module\GenericValueObject\Text;
use Project\Module\GenericValueObject\Title;
use Project\Service\Logger;
use Swift_Attachment;
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

    /** @var null | Logger $logger */
    protected $logger;

    /** @var Content $content */
    protected $content;

    /** @var Configuration $configuration */
    protected $configuration;

    /**
     * MailerService constructor.
     *
     * @param Configuration $configuration
     * @param Content       $content
     *
     * @param bool          $useLogger
     */
    public function __construct(Configuration $configuration, Content $content, bool $useLogger = true)
    {
        $mailConfiguration = $configuration->getEntryByName(self::MAILER_CONFIG_KEY);

        $this->configuration = $configuration;

        if ($this->validateMailerConfig($mailConfiguration) === true) {
            $this->mailerConfiguration = $mailConfiguration;

            // Create the Transport
            $this->transport = (new Swift_SmtpTransport($this->mailerConfiguration['server'], $this->mailerConfiguration['port']))->setUsername($this->mailerConfiguration['user'])->setPassword($this->mailerConfiguration['password']);

            // Create the Mailer using your created Transport
            $this->mailer = new Swift_Mailer($this->transport);
        } else {
            throw new InvalidArgumentException('Mailer could not be initialized.');
        }

        $this->content = $content;

        if ($useLogger === true) {
            $this->logger = Logger::getInstance();
        }
    }

    /**
     * @param Email       $to
     * @param Title       $subject
     * @param Text        $message
     * @param string|null $name
     * @param string|null $filePath
     * @param string      $fileName
     *
     * @return bool
     */
    public function sendSingleStandardMail(Email $to, Title $subject, Text $message, string $name = null, string $filePath = null, string $fileName = ''): bool
    {
        $backup = null;

        if (ENVIRONMENT === 'testing') {
            $to = Email::fromString($this->mailerConfiguration['testing']);
        } else {
            $backup = Email::fromString($this->mailerConfiguration['backup']);
        }

        // Create a message
        /** @var Swift_Message $mailMessage */
        $mailMessage = $this->buildMessage($to, $subject, $message, $filePath, $fileName, $backup, $name);

        $sent = false;
        if ($this->mailer->send($mailMessage, $this->errors) > 0) {
            $sent = true;
        }

        if ($this->logger !== null) {
            if ($sent === true) {
                $this->logger->addNotice($to->getEmail() . ' verschickt');
            } else {
                $this->logger->addNotice($to->getEmail() . ' nicht verschickt');
            }
        }

        return $sent;
    }

    /**
     * @return Swift_Mailer
     */
    public function getMailer(): Swift_Mailer
    {
        return $this->mailer;
    }

    /**
     * @param Email       $to
     * @param Title       $subject
     * @param Text        $message
     *
     * @param string      $filePath
     *
     * @param string      $fileName
     *
     * @param Email|null  $backup
     *
     * @param string|null $name
     *
     * @return Swift_Message
     */
    protected function buildMessage(
        Email $to,
        Title $subject,
        Text $message,
        string $filePath = null,
        string $fileName = '',
        Email $backup = null,
        string $name = null
    ): Swift_Message {
        $standardMailName = null;
        if (empty($this->mailerConfiguration['standard_from_name']) === false) {
            $standardMailName = $this->mailerConfiguration['standard_from_name'];
        }
        $swiftMessage = new Swift_Message($subject->getTitle());

        $mailBody = $this->content->getEntryByName('mail/header') . $message->getText() . $this->content->getEntryByName('mail/footer');

        $swiftMessage->setFrom([$this->mailerConfiguration['standard_from_mail'] => $standardMailName]);
        $swiftMessage->setTo($to->getEmail(), $name);
        $swiftMessage->setBody($mailBody, 'text/html');

        if (empty($filePath) === false) {
            $attachment = Swift_Attachment::fromPath($filePath);

            if (empty($fileName) === true) {
                $fileName = 'anhang.pdf';
            }

            $attachment->setFilename($fileName);
            $swiftMessage->attach($attachment);
        }

        if ($backup !== null) {
            $swiftMessage->setBcc($backup->getEmail());
        }

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