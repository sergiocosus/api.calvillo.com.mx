<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 15/03/17
 * Time: 12:07 PM
 */

namespace CalvilloComMx\Core\Picture;


use CalvilloComMx\Core\Category;
use CalvilloComMx\Core\ImageResizeService;
use CalvilloComMx\Core\Picture;
use CalvilloComMx\Core\Social\FacebookPageService;
use Carbon\Carbon;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;

class PictureService
{
    /**
     * @var ImageResizeService
     */
    private $imageResizeService;
    /**
    /**
     * @var FacebookPageService
     */
    private $fbp;

    /**
     * CategoryService constructor.
     */
    public function __construct(ImageResizeService $imageResizeService, FacebookPageService $fbp)
    {
        $this->imageResizeService = $imageResizeService;
        $this->fbp = $fbp;
    }

    public function create($data)
    {
        $data['image_code'] = $this->imageResizeService->saveAndResizeImagesFromBase64($data['image'], 'picture');

        if (isset($data['taken_at'])) {
            $data['taken_at'] = new Carbon($data['taken_at']);
        }

        \DB::beginTransaction();
        $picture = Picture::create($data);
        $picture->categories()->attach($data['categories']);
        \DB::commit();

        return $picture;
    }

    public function put(Picture $picture, $data)
    {
        if (isset($data['taken_at'])) {
            $data['taken_at'] = new Carbon($data['taken_at']);
        }

        \DB::beginTransaction();
        $picture->fill($data);
        $picture->update();
        $picture->categories()->sync($data['categories']);
        \DB::commit();

        return $picture;
    }

    public function postPictureOnFacebook(Picture $picture, Category $category, $data)
    {
        $this->fbp->init();
        return $this->fbp->postPhoto(
            $data['message'],
            "http://calvillo.com.mx/galeria/$category->link/foto/$picture->link",
            $picture->image_url,
            new Carbon(array_get($data,'scheduled_publish_time'))
        );
    }

    public function postLinkOnFacebook(Picture $picture, Category $category, $data)
    {
        $this->fbp->init();
        return $this->fbp->postLink(
            $data['message'],
            "http://calvillo.com.mx/galeria/$category->link/foto/$picture->link",
            new Carbon(array_get($data,'scheduled_publish_time'))
        );
    }

}