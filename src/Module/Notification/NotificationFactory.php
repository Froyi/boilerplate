<?php declare(strict_types=1);

namespace Project\Module\Notification;

use Project\Module\GenericValueObject\Message;

/**
 * Class NotificationFactory
 * @package     Project\Module\Notification
 */
class NotificationFactory
{
    /**
     * @param $object
     *
     * @return null|Notification
     */
    public function getNotificationByObject($object): ?Notification
    {
        return $this->getNotification($object->level, $object->message);
    }

    public function getNotification(string $level, string $message): ?Notification
    {
        try {
            $level = Level::fromString($level);
            $message = Message::fromString($message);

            return new Notification($level, $message);
        } catch (\InvalidArgumentException $exception) {
            return null;
        }
    }
}