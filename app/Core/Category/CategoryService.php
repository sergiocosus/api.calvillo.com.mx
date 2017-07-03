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

class CategoryService
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
}