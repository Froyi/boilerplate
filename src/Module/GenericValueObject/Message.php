<?php
declare (strict_types=1);

namespace Project\Module\GenericValueObject;

/**
 * Class Message
 * @package Project\Module\GenericValueObject
 */
class Message extends DefaultGenericValueObject
{
    /** @var int MIN_MESSAGE_LENGTH */
    protected const MIN_MESSAGE_LENGTH = 5;

    /** @var string $message */
    protected $message;

    /**
     * Message constructor.
     *
     * @param string $message
     */
    protected function __construct(string $message)
    {
        $this->message = $message;
    }

    /**
     * @param string $message
     *
     * @return Message
     * @throws \InvalidArgumentException
     */
    public static function fromString(string $message): self
    {
        self::ensureMessageIsValid($message);
        $message = self::convertMessage($message);

        return new self($message);
    }

    /**
     * @param string $message
     *
     * @throws \InvalidArgumentException
     */
    protected static function ensureMessageIsValid(string $message): void
    {
        if (\strlen($message) < self::MIN_MESSAGE_LENGTH) {
            throw new \InvalidArgumentException('The message is not long enough.', 1);
        }
    }

    /**
     * @param string $message
     *
     * @return string
     */
    protected static function convertMessage(string $message): string
    {
        return trim($message);
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->message;
    }
}

