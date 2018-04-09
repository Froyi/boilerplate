<?php declare (strict_types=1);

namespace Project\Controller;

use Project\Module\GenericValueObject\Email;
use Project\Module\Mailer\MailerService;

/**
 * Class MailerController
 * @package Project\Controller
 */
class MailerController extends DefaultController
{
    public function sendMailAction(): void
    {
        $to = Email::fromString('ms2002@onlinehome.de');
        $subject = 'Boilerplate Test';
        $message = 'This is a test Message.';

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