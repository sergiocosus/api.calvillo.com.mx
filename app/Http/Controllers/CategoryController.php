<?php

namespace CalvilloComMx\Http\Controllers;

use CalvilloComMx\Core\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * @var Category\CategoryService
     */
    private $categoryService;


    /**
     * CategoryController constructor.
     */
    public function __construct(Category\CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
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

        return $this->success(compact('category'));
    }

    public function post(Request $request)
    {
        $category = $this->categoryService->create(
            $request->all()
        );

        return $this->success(compact('category'));
    }
}
