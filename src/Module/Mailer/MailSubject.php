<?php
declare (strict_types=1);

namespace Project\Module\Mailer;

use Project\Module\GenericValueObject\Title;

/**
 * Class MailSubject
 * @package Project\Module\Mailer
 */
class MailSubject extends Title
{
    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->getTitle();
    }
}

