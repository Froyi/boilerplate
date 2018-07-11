<?php
declare (strict_types=1);

namespace Project\Module\Image;

use claviska\SimpleImage;
use Project\Module\DefaultModel;

/**
 * Class Image
 * @package Project\Module\Image
 */
class Image extends DefaultModel
{
    /** @var string PATH_NEWS */
    public const PATH_NEWS = 'data/img/news/';

    /** @var string PATH_ALBUM */
    public const PATH_ALBUM = 'data/img/galerie/';

    /** @var int SAVE_QUALITY */
    protected const SAVE_QUALITY = 50;

    /** @var int MAX_LENGTH */
    protected const MAX_LENGTH = 1200;

    /** @var bool AUTO_ORIENTATION */
    protected const AUTO_ORIENTATION = false;

    /** @var bool FIT_TO_ORIENTATION */
    protected const FIT_TO_ORIENTATION = false;

    /** @var bool SHARPEN */
    protected const SHARPEN = false;

    /** @var SimpleImage $image */
    protected $image;

    /** @var string $imagePath */
    protected $imagePath;

    /**
     * Image constructor.
     *
     * @param string $path
     *
     * @throws \Exception
     */
    protected function __construct(string $path)
    {
        parent::__construct();

        $this->image = new SimpleImage($path);

        $this->imagePath = $path;

        if (self::AUTO_ORIENTATION === true) {
            $this->image->autoOrient();
        }

        if (self::FIT_TO_ORIENTATION === true) {
            if ($this->image->getAspectRatio() >= 1) {
                $this->image->fitToWidth(self::MAX_LENGTH);
            } else {
                $this->image->fitToHeight(self::MAX_LENGTH);
            }
        }

        if (self::SHARPEN === true) {
            $this->image->sharpen();
        }
    }

    /**
     * @param string $path
     *
     * @return Image
     * @throws \Exception
     */
    public static function fromFile(string $path): self
    {
        return new self($path);
    }

    /**
     * @return string
     */
    public function getImagePath(): string
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
     *
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