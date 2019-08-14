<?php declare(strict_types=1);

namespace Project\Module\Notification;

use InvalidArgumentException;
use Project\Module\DefaultFactory;
use Project\Module\GenericValueObject\Message;

/**
 * Class NotificationFactory
 * @package     Project\Module\Notification
 */
class NotificationFactory extends DefaultFactory
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

    /**
     * @param string $levelString
     * @param string $messageString
     *
     * @return null|Notification
     */
    public function getNotification(string $levelString, string $messageString): ?Notification
    {
        try {
            $level = Level::fromString($levelString);
            $message = Message::fromString($messageString);

            return new Notification($level, $message);
        } catch (InvalidArgumentException $exception) {
            $this->logger->addCritical('NotificationFactory: ' . $exception->getMessage());

            return null;
        }
    }
}