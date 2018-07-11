<?php declare(strict_types=1);

namespace Project\Module\Notification;

use Project\Utilities\Tools;

/**
 * Class NotificationService
 * @package     Project\Module\Notification
 */
class NotificationService
{
    /** @var NotificationFactory $notificationFactory */
    protected $notificationFactory;

    /**
     * NotificationService constructor.
     */
    public function __construct()
    {
        $this->notificationFactory = new NotificationFactory();
    }

    /**
     * @param bool $keep
     *
     * @return array
     */
    public function getNotifications(bool $keep = false): array
    {
        $notifications = [];
        /** @var array $notificationsData */
        $notificationsData = Tools::getValue('notifications');

        if ($notificationsData === false || \is_array($notificationsData) === false) {
            return $notifications;
        }

        foreach ($notificationsData as $notificationData) {
            $notification = $this->notificationFactory->getNotificationByObject(unserialize($notificationData,
                ['allowed_classes' => ['stdClass']]));

            if ($notification !== null) {
                $notifications[] = $notification;
            }
        }

        if ($keep === false) {
            unset($_SESSION[Notification::SESSION_NOTIFICATION_NAME]);
        }

        return $notifications;
    }

    /**
     * @param string $message
     *
     * @return bool
     */
    public function setSuccess(string $message): bool
    {
        return $this->setNotification(Level::LEVEL_SUCCESS, $message);
    }

    /**
     * @param string $message
     *
     * @return bool
     */
    public function setError(string $message): bool
    {
        return $this->setNotification(Level::LEVEL_ERROR, $message);
    }

    /**
     * @param string $message
     *
     * @return bool
     */
    public function setAlert(string $message): bool
    {
        return $this->setNotification(Level::LEVEL_ALERT, $message);
    }

    /**
     * @param string $message
     *
     * @return bool
     */
    public function setInfo(string $message): bool
    {
        return $this->setNotification(Level::LEVEL_INFO, $message);
    }

    /**
     * @param string $level
     * @param string $message
     *
     * @return bool
     */
    protected function setNotification(string $level, string $message): bool
    {
        $notification = $this->notificationFactory->getNotification($level, $message);

        if ($notification === null) {
            return false;
        }

        $_SESSION[Notification::SESSION_NOTIFICATION_NAME][] = serialize($notification->toSession());

        return true;
    }
}