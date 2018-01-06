<?php

namespace CalvilloComMx\Http\Controllers;

use CalvilloComMx\Core\Category;
use Illuminate\Auth\AuthManager;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * @var Category\CategoryService
     */
    private $categoryService;
    /**
     * @var \Auth
     */
    private $auth;


    /**
     * CategoryController constructor.
     */
    public function __construct(Category\CategoryService $categoryService,
                                AuthManager $auth)
    {
        $this->categoryService = $categoryService;
        $this->auth = $auth;
    }

    public function getAll() {
        $categories = Category::with('category')
            ->select('categories.*')
            ->join('categories as parentCategory', 'categories.category_id', '=', 'parentCategory.id')
            ->orderBy('parentCategory.title')
            ->orderBy('categories.title')->get();

        return $this->success(compact('categories'));
    }

    public function get(Category $category)
    {
        $category->load([
            'category',
            'categories',
            'videos',
            'directories',
            'pictures',
        ]);

        $category->append('related_categories');

        if ($this->auth->check()) {
            $category->load([
                'deletedPictures',
                'deletedDirectories',
                'deletedCategories',
                'pictures.categories',
                'directories.categories',
            ]);
        }

        return $this->success(compact('category'));
    }

    public function getSubCategories(Category $category)
    {
        $category->load([
            'categories.category',
            'categories.categories',
            'categories.videos',
            'categories.directories',
            'categories.pictures',
        ]);

        $categories = $category->categories;

        return $this->success(compact('categories'));
    }

    public function post(Request $request)
    {
        $category = $this->categoryService->create(
            $request->all()
        );

        return $this->success(compact('category'));
    }

    public function put(Category $category, Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'link' => 'required|max:255',
        ]);

        $category = $this->categoryService->put(
            $category, $request->all()
        );

        return $this->success(compact('category'));
    }

    public function delete(Category $category)
    {
        $category->delete();

        return $this->success(compact('category'));
    }

    public function deleteForce($category_id)
    {
        $category = Category::whereKey($category_id)->onlyTrashed()->first();
        if (!$category) {
            throw new ModelNotFoundException(Picture::class);
        }
        $category->forceDelete();

        return $this->success();
    }

    public function patch($picture_id)
    {
        $category = Category::whereKey($picture_id)->onlyTrashed()->first();
        if (!$category) {
            throw new ModelNotFoundException(Category::class);
        }
        $category->restore();

        return $this->success(compact('category'));
    }

    public function getNewest(Request $request)
    {
        $elements = $request->get('elements', 50);

        $categories = Category::orderBy('created_at', 'desc')
            ->has('category')
            ->with('pictures')
            ->has('pictures', '>=',5)
            ->limit($elements)->get();
        return $this->success(compact('categories'));
    }

    public function postFacebook(Category $category, Request $request)
    {
        $this->validate($request, [
            'hours_interval' => 'required',
        ]);

        $facebook_post_ids = $this->categoryService
            ->postLinksOfPicturesOnFacebook($request, $category);
        return $this->success(compact('facebook_post_ids'));
    }
}
