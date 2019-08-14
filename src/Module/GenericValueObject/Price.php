<?php
declare(strict_types=1);

namespace Project\Module\GenericValueObject;

use InvalidArgumentException;

/**
 * Class Price
 * @package     Project\Module\GenericValueObject
 */
class Price extends Money
{
    /**
     * @param $price
     *
     * @return Price
     * @throws InvalidArgumentException
     */
    public static function fromValue($price): self
    {
        parent::ensureMoneyIsValid($price);
        $price = parent::convertMoney($price);
        return new self($price);
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        if ($this->getMoney() === 0) {
            return 0;
        }

        return $this->getMoney() / 100;
    }

    /**
     * @param int $price
     *
     * @return Price
     */
    public function add(int $price): self
    {
        $this->money += $price * 100;

        return new self($this->money);
    }
}