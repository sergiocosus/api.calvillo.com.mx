<?php

namespace CalvilloComMx\Http\Controllers;

use CalvilloComMx\Core\Directory;
use CalvilloComMx\Core\Directory\DirectoryService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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

    public function getAll()
    {
        $directories = Directory::get();

        return $this->success(compact('directories'));
    }

    public function post(Request $request)
    {
        $directory = $this->directoryService->create(
            $request->all()
        );

        return $this->success(compact('directory'));
    }


    public function put(Directory $directory, Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'link' => 'required|max:255',
        ]);

        $directory = $this->directoryService->put(
            $directory, $request->all()
        );

        return $this->success(compact('directory'));
    }

    public function delete(Directory $directory)
    {
        $directory->delete();

        return $this->success(compact('directory'));
    }

    public function deleteForce($directory_id)
    {
        $directory = Directory::whereKey($directory_id)->onlyTrashed()->first();
        if (!$directory) {
            throw new ModelNotFoundException(Picture::class);
        }
        $directory->forceDelete();

        return $this->success();
    }

    public function patch($picture_id)
    {
        $directory = Directory::whereKey($picture_id)->onlyTrashed()->first();
        if (!$directory) {
            throw (new ModelNotFoundException())->setModel(Directory::class);
        }
        $directory->restore();

        return $this->success(compact('directory'));
    }
}
