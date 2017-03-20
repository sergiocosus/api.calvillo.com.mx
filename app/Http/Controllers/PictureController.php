<?php

namespace CalvilloComMx\Http\Controllers;

use CalvilloComMx\Core\Picture\PictureService;
use Illuminate\Http\Request;

class PictureController extends Controller
{
    /**
     * @var PictureService
     */
    private $pictureService;

    /**
     * PictureController constructor.
     */
    public function __construct(PictureService $pictureService)
    {
        $this->pictureService = $pictureService;
    }

    public function post(Request $request)
    {
        $picture = $this->pictureService->create(
            $request->all()
        );

        return $this->success(compact('picture'));
    }
}
