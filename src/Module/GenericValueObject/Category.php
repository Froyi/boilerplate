<?php
declare (strict_types=1);

namespace Project\Module\GenericValueObject;

use InvalidArgumentException;
use function strlen;

/**
 * Class Category
 * @package Project\Module\GenericValueObject
 */
class Category extends DefaultGenericValueObject
{
    public const COMPETITION = 'competition';
    public const PERMANENT_STARTER = 'permanentStarter';

    protected const CATEGORY_MIN_LENGTH = 5;

    /** @var string $category */
    protected $category;

    /**
     * Category constructor.
     *
     * @param string $category
     */
    protected function __construct(string $category)
    {
        $this->category = $category;
    }

    /**
     * @param string $category
     *
     * @return Category
     */
    public static function fromString(string $category): self
    {
        $category = self::convertCategory($category);

        self::ensureCategoryIsValid($category);

        return new self($category);
    }

    /**
     * @param string $category
     *
     */
    protected static function ensureCategoryIsValid(string $category): void
    {
        if (strlen($category) < self::CATEGORY_MIN_LENGTH) {
            throw new InvalidArgumentException('The category is too short', 1);
        }
    }

    /**
     * @param string $category
     *
     * @return string
     */
    protected static function convertCategory(string $category): string
    {
        return trim($category);
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->category;
    }
}

