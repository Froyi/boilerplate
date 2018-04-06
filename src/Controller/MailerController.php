<?php declare (strict_types=1);

namespace Project\Controller;

use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

/**
 * Class MailerController
 * @package Project\Controller
 */
class MailerController extends DefaultController
{
    public function sendMailAction(): void
    {
        // Create the Transport
        $transport = (new Swift_SmtpTransport('localhost', 25))->setUsername('web1061p1')->setPassword('j5q9hCZp');
        // Create the Mailer using your created Transport
        $mailer = new Swift_Mailer($transport);

        $logger = new \Swift_Plugins_Loggers_ArrayLogger();
        $mailer->registerPlugin(new \Swift_Plugins_LoggerPlugin($logger));

        // Create a message
        $message = (new Swift_Message('Wonderful Subject'))->setFrom(['test@boilerplate.ms2002.alfahosting.org' => 'John Doe'])->setTo('ms2002@onlinehome.de')->setBody('Here is the message itself');
        if (!$mailer->send($message, $errors)) {
            // Dump the log contents
            // NOTE: The EchoLogger dumps in realtime so dump() does nothing for it. We use ArrayLogger instead.
            echo "Error:" . $logger->dump();
        } else {
            echo "Successfull.";
        }
    }
}