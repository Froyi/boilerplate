<?php declare(strict_types=1);

namespace Project\Module\Notification;

use Project\Module\GenericValueObject\DefaultGenericValueObject;

/**
 * Class Level
 * @package     Project\Module\Notification
 */
class Level extends DefaultGenericValueObject
{
    /** @var string LEVEL_ERROR */
    public const LEVEL_ERROR = 'error';

    /** @var string LEVEL_SUCCESS */
    public const LEVEL_SUCCESS = 'success';

    /** @var string LEVEL_ALERT */
    public const LEVEL_ALERT = 'alert';

    /** @var string LEVEL_INFO */
    public const LEVEL_INFO = 'info';

    /** @var array VALID_LEVELS */
    public const VALID_LEVELS = [
        self::LEVEL_ERROR,
        self::LEVEL_SUCCESS,
        self::LEVEL_ALERT,
        self::LEVEL_INFO
    ];

    /** @var string $level */
    protected $level;

    /**
     * @param string $level
     *
     * @return Level
     * @throws \InvalidArgumentException
     */
    public static function fromString(string $level): self
    {
        self::ensureLevelIsValid($level);

        return new self($level);
    }

    /**
     * Level constructor.
     *
     * @param string $level
     */
    protected function __construct(string $level)
    {
        $this->level = $level;
    }

    /**
     * @param string $level
     *
     * @throws \InvalidArgumentException
     */
    protected static function ensureLevelIsValid(string $level): void
    {
        if (\in_array($level, self::VALID_LEVELS, true) === false) {
            throw new \InvalidArgumentException('This level is not valid: ' . $level);
        }
    }

    /**
     * @return string
     */
    public function getLevel(): string
    {
        return $this->level;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getLevel();
    }
}