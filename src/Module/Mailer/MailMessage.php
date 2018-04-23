<?php
declare (strict_types=1);

namespace Project\Module\Mailer;

use Project\Module\GenericValueObject\Text;

/**
 * Class MailMessage
 * @package Project\Module\Mailer
 */
class MailMessage extends Text
{
    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->getText();
    }
}

