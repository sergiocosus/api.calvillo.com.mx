<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 12/12/16
 * Time: 04:19 PM
 */

namespace CalvilloComMx\Core;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Intervention\Image\ImageManager;

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

    public function resize($path, $watermark = false)
    {
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
        }
    }
}