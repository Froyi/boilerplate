<?php declare(strict_types=1);

namespace Project\Module\Image;

/**
 * Class ImageService
 * @package     Project\Module\Image
 * @copyright   Copyright (c) 2018 Maik Schößler
 */
class ImageService
{
    /**
     * @param $path
     *
     * @return Image
     * @throws \Exception
     */
    public function getImageFromPath($path): Image
    {
        return Image::fromFile($path);
    }

    /**
     * @param array  $uploadedFile
     * @param string $path
     *
     * @return null|Image
     * @throws \Exception
     */
    public static function fromUploadWithSave(array $uploadedFile, string $path): ?Image
    {
        if ($image = Image::fromFile($uploadedFile['tmp_name'])) {
            $filePath = $path . $uploadedFile['name'];

            if ($image->saveToPath($filePath) === true) {
                return $image;
            }
        }

        return null;
    }
}