<?php declare(strict_types=1);

namespace Project\Module\Notification;

use Project\Module\DefaultModel;
use Project\Module\GenericValueObject\Message;

/**
 * Class Notification
 * @package     Project\Module\Notification
 */
class Notification extends DefaultModel
{
    /** @var string SESSION_NOTIFICATION_NAME */
    public const SESSION_NOTIFICATION_NAME = 'notifications';

    /** @var Level $level */
    protected $level;

    /** @var Message $message */
    protected $message;

    /**
     * Notification constructor.
     *
     * @param Level   $level
     * @param Message $message
     */
    public function __construct(Level $level, Message $message)
    {
        parent::__construct();

        $this->level = $level;
        $this->message = $message;
    }

    /**
     * @return Level
     */
    public function getLevel(): Level
    {
        return $this->level;
    }

    /**
     * @return Message
     */
    public function getMessage(): Message
    {
        return $this->message;
    }

    public function toSession(): \stdClass
    {
        $object = new \stdClass();

        $object->level = $this->level->getLevel();
        $object->message = $this->message->getMessage();

        return $object;
    }
}