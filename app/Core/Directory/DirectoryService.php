<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 15/03/17
 * Time: 12:13 PM
 */

namespace CalvilloComMx\Core\Directory;


use CalvilloComMx\Core\Directory;
use CalvilloComMx\Core\ImageResizeService;

class DirectoryService
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
        $data['image_code'] = $this->imageResizeService->saveAndResizeImagesFromBase64($data['image'], 'directory');

        \DB::beginTransaction();
        $directory = Directory::create($data);
        $directory->categories()->attach($data['category_id']);
        \DB::commit();

        return $directory;
    }

    public function put(Directory $directory, $data)
    {
        if (isset($data['taken_at'])) {
            $data['taken_at'] = new Carbon($data['taken_at']);
        }

        \DB::beginTransaction();
        $directory->fill($data);

        if (isset($data['image'])) {
            $directory->image_code = $this->imageResizeService->saveAndResizeImagesFromBase64(
                $data['image'], 'directory'
            );
        }

        $directory->update();

        \DB::commit();

        return $directory;
    }

}