<?php

namespace CalvilloComMx\Http\Controllers;

use CalvilloComMx\Core\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function getLink(Category $category)
    {
        $category->load([
            'categories',
            'pictures',
            'videos',
            'directories'
        ]);

        $this->success(compact('category'));
    }
}
