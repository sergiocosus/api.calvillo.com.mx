<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 15/03/17
 * Time: 09:47 AM
 */

namespace CalvilloComMx\Core\Category;


use CalvilloComMx\Core\Category;
use CalvilloComMx\Core\ImageResizeService;
use CalvilloComMx\Core\Social\FacebookPageService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CategoryService
{
    /**
     * @var ImageResizeService
     */
    private $imageResizeService;
    /**
     * @var FacebookPageService
     */
    private $fbp;

    /**
     * CategoryService constructor.
     */
    public function __construct(ImageResizeService $imageResizeService,
                                FacebookPageService $fbp)
    {
        $this->imageResizeService = $imageResizeService;
        $this->fbp = $fbp;
    }

    public function create($data)
    {
        $data['image_code'] = $this->imageResizeService->saveAndResizeImagesFromBase64($data['image'], 'category');

        $category = Category::create($data);

        return $category;
    }

    public function put(Category $category, $data)
    {
        \DB::beginTransaction();
        $category = $category->fill($data);

        if (isset($data['image'])) {
            $category->image_code = $this->imageResizeService->saveAndResizeImagesFromBase64(
                $data['image'], 'category'
            );
        }

        $category->update();
        \DB::commit();
        return $category;
    }

    public function postLinksOfPicturesOnFacebook(Request $request, Category $category)
    {
        $publication_at = new Carbon(null, 'America/Mexico_City');
logger($publication_at);
logger($publication_at->timestamp);
        $hours = $request->get('hours_interval', 3);

        $this->fbp->init();
        $ids = [];
        foreach ($category->pictures as $picture) {
            $publication_at = $publication_at->addHours($hours);

            if ($publication_at->hour > 21) {
                $publication_at = $publication_at->addDay()->setTime(8, 0);
            } elseif($publication_at->hour < 8) {
                $publication_at = $publication_at->setTime(8, 0);
            }

            logger( $publication_at);
            $ids[] = $this->fbp->postLink(
                '',
                "http://calvillo.com.mx/galeria/$category->link/foto/$picture->link",
                $publication_at
            );
        }

        return $ids;

    }
}