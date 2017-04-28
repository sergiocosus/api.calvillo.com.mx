<?php

namespace CalvilloComMx\Http\Controllers;

use CalvilloComMx\Core\Picture;
use CalvilloComMx\Core\Picture\PictureService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
        $this->validate($request, [
            'title' => 'required|max:255',
            'link' => 'required|unique:pictures|max:255',
            'taken_at' => 'nullable|date',
        ]);

        $picture = $this->pictureService->create(
            $request->all()
        );

        return $this->success(compact('picture'));
    }

    public function put(Picture $picture, Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'link' => 'required|max:255',
            'taken_at' => 'nullable|date',
        ]);

        $picture = $this->pictureService->put(
            $picture, $request->all()
        );

        return $this->success(compact('picture'));
    }

    public function delete(Picture $picture)
    {
        $picture->delete();

        return $this->success(compact('picture'));
    }

    public function deleteForce($picture_id)
    {
        $picture = Picture::whereKey($picture_id)->onlyTrashed()->first();
        if (!$picture) {
            throw new ModelNotFoundException(Picture::class);
        }
        $picture->forceDelete();

        return $this->success();
    }

    public function patch($picture_id)
    {
        $picture = Picture::whereKey($picture_id)->onlyTrashed()->first();
        if (!$picture) {
            throw new ModelNotFoundException(Picture::class);
        }
        $picture->restore();

        return $this->success(compact('picture'));
    }

    public function getLinkExists(Request $request) {
        $exists = (boolean)Picture::whereLink($request->get('link'))->first(['id']);

        return $this->success(compact('exists'));
    }
}
