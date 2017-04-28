<?php

namespace CalvilloComMx\Http\Controllers;

use CalvilloComMx\Core\Category;
use Illuminate\Auth\AuthManager;
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



    public function get(Category $category)
    {
        $category->load([
            'category',
            'categories',
            'pictures',
            'videos',
            'directories'
        ]);

        if ($this->auth->check()) {
            $category->load([
                'deletedPictures'
            ]);
        }

        return $this->success(compact('category'));
    }

    public function post(Request $request)
    {
        $category = $this->categoryService->create(
            $request->all()
        );

        return $this->success(compact('category'));
    }

    public function getNewest(Request $request)
    {
        $elements = $request->get('elements', 50);

        $categories = Category::orderBy('created_at', 'desc')
            ->limit($elements)->get();

        return $this->success(compact('categories'));
    }
}
