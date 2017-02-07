<?php

namespace CalvilloComMx\Http\Controllers;

use CalvilloComMx\Core\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
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
}
