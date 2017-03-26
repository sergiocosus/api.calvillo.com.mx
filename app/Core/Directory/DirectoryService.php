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

        $directory = Directory::create($data);

        return $directory;
    }
}