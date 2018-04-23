<?php declare (strict_types=1);

namespace Project\Controller;

use Project\Module\GenericValueObject\Email;
use Project\Module\Mailer\MailerService;
use Project\Module\Mailer\MailMessage;
use Project\Module\Mailer\MailSubject;

/**
 * Class MailerController
 * @package Project\Controller
 */
class MailerController extends DefaultController
{
    public function sendMailAction(): void
    {
        $to = Email::fromString('ms2002@onlinehome.de');
        /** @var MailSubject $subject */
        $subject = MailSubject::fromString('Boilerplate Test');
        /** @var MailMessage $message */
        $message = MailMessage::fromString('This is a test Message.');

        $mailerService = null;
        try {
            $mailerService = new MailerService($this->configuration, true);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        if ($mailerService !== null) {
            $mailerService->sendSingleStandardMail($to, $subject, $message);
        }
    }
}