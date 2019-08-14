<?php
declare(strict_types = 1);

namespace Project\Module\GenericValueObject;

use InvalidArgumentException;
use function is_float;
use function is_int;
use function is_string;

/**
 * Class Money
 * @package     Project\Module\GenericValueObject
 */
abstract class Money
{
    /**
     * @var int
     */
    protected $money;

    /**
     * Money constructor.
     *
     * @param int $money
     */
    protected function __construct(int $money)
	{
		$this->money = $money;
	}

    /**
     * @param $money
     *
     * @return mixed
     */
    abstract public static function fromValue($money);

    /**
     * @param $money
     *
     * @throws InvalidArgumentException
     */
    protected static function ensureMoneyIsValid($money): void
	{
		if (is_float($money) === false && is_int($money) === false && is_string($money) === false){
			throw new InvalidArgumentException('This is not a number: ' . $money);
		}
		
		if ($money < 0) {
			throw new InvalidArgumentException('This is not a positive number: ' . $money, 1);
		}
	}

    /**
     * @param $money
     *
     * @return int
     */
    protected static function convertMoney($money): int
	{
		$money *= 100;
		$money = floor($money);
		$money = (int)$money; 

		return $money;
	}

    /**
     * @return int
     */
    public function getMoney(): int
	{
		return $this->money;
	}

    /**
     * @return string
     */
    public function __toString(): string
	{
		return number_format($this->money / 100, 2, ',', '.');
	}

    /**
     * @return string
     */
    public function toString(): string
    {
        return number_format($this->money / 100, 2, ',', '.');
    }
}