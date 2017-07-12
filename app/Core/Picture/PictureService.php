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
use Carbon\Carbon;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;

class PictureService
{
    /**
     * @var ImageResizeService
     */
    private $imageResizeService;
    /**
     * @var LaravelFacebookSdk
     */
    private $fb;

    /**
     * CategoryService constructor.
     */
    public function __construct(ImageResizeService $imageResizeService, LaravelFacebookSdk $fb)
    {
        $this->imageResizeService = $imageResizeService;
        $this->fb = $fb;
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

    public function postOnFacebook(Picture $picture, Category $category, $message) {
        $facebookPageId = env('FACEBOOK_PAGE_ID');

        if(!$socialToken = \Auth::user()->socialToken) {
            throw new \Exception('No facebook token!');
        }

        $this->fb->setDefaultAccessToken($socialToken->facebook_access_token);
        $res = $this->fb->get('/me/accounts/'.$facebookPageId);
        $token = $res->getGraphEdge()->getField(0)->getField('access_token');
        $this->fb->setDefaultAccessToken($token);

        $url = "http://calvillo.com.mx/galeria/$category->link/foto/$picture->link";

        \Log::info($picture->image_url);
        $res = $this->fb->post("/$facebookPageId/photos/", [
            'message'=>"$message \n$url",
            'url' => $picture->image_url,
        ]);

        return $res->getDecodedBody()['id'];
    }

}