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
use Carbon\Carbon;

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
        $data['image_code'] = $this->imageResizeService->saveAndResizeImagesFromBase64($data['image'], 'picture');

        if (isset($data['taken_at'])) {
            $data['taken_at'] = new Carbon($data['taken_at']);
        }

        \DB::beginTransaction();
        $picture = Picture::create($data);
        $picture->categories()->attach($data['category_id']);
        \DB::commit();

        return $picture;
    }
}