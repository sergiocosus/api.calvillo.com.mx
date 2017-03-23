<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 12/12/16
 * Time: 04:19 PM
 */

namespace CalvilloComMx\Core;


use Illuminate\Http\File;
use Intervention\Image\ImageManager;
use Storage;

class ImageResizeService
{
    /**
     * @var ImageManager
     */
    private $imageManager;

    public $sizes = [
        'xs' => 160,
        'sm' => 320,
        'md' => 640,
        'lg' => 1280,
        'xlg' => 1920,
    ];

    /**
     * ImageResizeService constructor.
     */
    public function __construct(ImageManager $imageManager)
    {
        $this->imageManager = $imageManager;
    }

    public function saveAndResizeImagesFromBase64(string $base64File, string $path) {
        $image_code = uniqid();
        $decoded_data = base64_decode($base64File);
        $tempFilename = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $image_code;
        file_put_contents($tempFilename, $decoded_data);

        $this->resizeAndSaveOnStorage($tempFilename, $path);

        return $image_code;
    }

    public function resizeAndSaveOnStorage($tempFilename, $path)
    {
        $createdImagesPath = $this->resize($tempFilename);

        foreach ($createdImagesPath as $createdImagePath) {
            $basename = basename($createdImagePath);
            Storage::disk('public')->putFileAs("images/$path", new File($createdImagePath), $basename);
        }
    }

    public function resize($path, $watermark = false)
    {
        $createdImagesPath = [$path];

        foreach ($this->sizes as $name => $size) {
            $image = $this->imageManager->make($path);
            if ($watermark) {
                // TODO Put Watermark
            }
            $image->resize($size, $size, function($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $image->save($path . '_' . $name);
            $createdImagesPath[] = $path . '_' . $name;
        }

        return $createdImagesPath;
    }
}