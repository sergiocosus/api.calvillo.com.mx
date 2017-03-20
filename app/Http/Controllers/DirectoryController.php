<?php

namespace CalvilloComMx\Http\Controllers;

use CalvilloComMx\Core\Directory\DirectoryService;
use Illuminate\Http\Request;

class DirectoryController extends Controller
{
    /**
     * @var DirectoryService
     */
    private $directoryService;

    /**
     * PictureController constructor.
     */
    public function __construct(DirectoryService $directoryService)
    {
        $this->directoryService = $directoryService;
    }

    public function post(Request $request)
    {
        $directory = $this->directoryService->create(
            $request->all()
        );

        return $this->success(compact('directory'));
    }
}
