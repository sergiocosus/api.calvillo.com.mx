<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 15/03/17
 * Time: 12:07 PM
 */

namespace CalvilloComMx\Core\Picture;


use CalvilloComMx\Core\ImageResizeService;
use CalvilloComMx\Core\Picture;

class PictureService
{
    /**
     * @var ImageResizeService
     */
    private $imageResizeService;

    /**
     * CategoryService constructor.
     */
    public function __construct(ImageResizeService $imageResizeService)
    {
        $this->imageResizeService = $imageResizeService;
    }

    public function create($data)
    {
        $data['image_code'] = $this->imageResizeService->resize($data['image'], 'picture');

        $picture = Picture::create($data);

        return $picture;
    }
}