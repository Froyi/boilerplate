<?php
declare (strict_types=1);

namespace Project\Module\GenericValueObject;

use InvalidArgumentException;
use function strlen;

/**
 * Class Author
 * @package Project\Module\GenericValueObject
 */
class Author extends DefaultGenericValueObject
{
    protected const AUTHOR_MIN_LENGTH = 5;

    /** @var string $author */
    protected $author;

    /**
     * Author constructor.
     * @param string $author
     */
    protected function __construct(string $author)
    {
        $this->author = $author;
    }

    /**
     * @param string $author
     * @return Author
     */
    public static function fromString(string $author): self
    {
        self::ensureAuthorIsValid($author);

        return new self($author);
    }

    /**
     * @param string $author
     */
    protected static function ensureAuthorIsValid(string $author): void
    {
        if (strlen($author) < self::AUTHOR_MIN_LENGTH) {
            throw new InvalidArgumentException('The author is too short', 1);
        }
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->author;
    }
}

