<?php
declare (strict_types=1);

namespace Project\Controller;

use Project\Configuration;
use Project\Module\Mailer\MailerService;

/**
 * Class MailerController
 * @package Project\Controller
 */
class MailerController extends DefaultController
{
    /** @var MailerService $mailerService */
    protected $mailerService;

    /**
     * MailerController constructor.
     *
     * @param Configuration $configuration
     */
    public function __construct(Configuration $configuration)
    {
        parent::__construct($configuration);

        $this->mailerService = new MailerService($configuration, $this->content);
    }
}