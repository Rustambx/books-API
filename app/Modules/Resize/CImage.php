<?php
namespace App\Modules\Resize;
use Image;

/**
 * Class CImage
 * @package App\Lib\File
 */
class CImage
{
    /**
     * Ресайз изображений
     * @param string $fileRelativePath
     * @param int $width
     * @param int $height
     * @return string
     */

    /// Изменение размера картинок
    public static function resize (string $fileRelativePath, int $width, int $height)
    {
        $imageRealPath = $_SERVER['DOCUMENT_ROOT'].'/storage/upload/images/'.$fileRelativePath;
        if (!file_exists($imageRealPath)) {
            return "";
        }
        $arImage = pathinfo($imageRealPath);
        $resizeFileName = $arImage['filename'] . "_resize_" . $height . "x" . $width . "." . $arImage['extension'];
        $resizeRelativePath = str_replace($_SERVER['DOCUMENT_ROOT'], '', $arImage['dirname']) . "/" . $resizeFileName;
        $resizeRealPath = $arImage['dirname'] . "/" . $resizeFileName;

        if (!file_exists($resizeRealPath)) {
            $obImage = Image::make($imageRealPath);
            $obImage->fit($width, $height);
            $obImage->save($resizeRealPath);
        }

        return $resizeRelativePath;
    }
}
