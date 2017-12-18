<?php
declare (strict_types=1);

namespace Project\Module\GenericValueObject;

use claviska\SimpleImage;

/**
 * Class Image
 * @package Project\Module\GenericValueObject
 */
class Image
{
    public const PATH_NEWS = 'data/img/news/';
    public const PATH_ALBUM = 'data/img/galerie/';

    protected const SAVE_QUALITY = 50;
    protected const MAX_LENGTH = 1200;

    /** @var SimpleImage $image */
    protected $image;

    /** @var string $imagePath */
    protected $imagePath;

    /** @var  array $tempImage */
    protected $tempImage;

    /**
     * Image constructor.
     * @param string $path
     */
    protected function __construct(string $path)
    {
        $this->image = new SimpleImage($path);
        $this->imagePath = $path;
        $this->image->autoOrient();

        if ($this->image->getAspectRatio() >= 1) {
            $this->image->fitToWidth(self::MAX_LENGTH);
        } else {
            $this->image->fitToHeight(self::MAX_LENGTH);
        }

        $this->image->sharpen();
    }

    /**
     * @param string $path
     * @return Image
     */
    public static function fromFile(string $path): self
    {
        return new self($path);
    }

    /**
     * @param array $uploadedFile
     * @param string $path
     * @return null|Image
     */
    public static function fromUploadWithSave(array $uploadedFile, string $path): ?self
    {
        $image = self::fromFile($uploadedFile['tmp_name']);
        $filePath = $path . $uploadedFile['name'];

        if ($image->saveToPath($filePath) === true) {
            return $image;
        }

        return null;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return (string)$this->imagePath;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->imagePath;
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->image->getWidth();
    }

    /**
     * @return SimpleImage
     */
    public function getImage(): SimpleImage
    {
        return $this->image;
    }

    /**
     * @param string $path
     * @return bool
     * @throws \Exception
     */
    public function saveToPath(string $path): bool
    {
        try {
            $this->image->toFile($path, null, self::SAVE_QUALITY);
        } catch (\InvalidArgumentException $error) {
            return false;
        }

        $this->imagePath = $path;

        return true;
    }
}