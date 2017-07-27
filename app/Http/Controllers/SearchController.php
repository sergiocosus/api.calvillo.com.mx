<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 24/07/17
 * Time: 02:03 PM
 */

namespace CalvilloComMx\Http\Controllers;


use CalvilloComMx\Core\Category;
use CalvilloComMx\Core\Directory;
use CalvilloComMx\Core\Picture;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function get(Request $request)
    {
        $categories = Category::search($request->get('query'))->paginate(10)->items();
        $pictures = Picture::search($request->get('query'))->paginate(10)->load('categories');
        $directories = Directory::search($request->get('query'))->paginate(10)->load('categories');


        return $this->success(compact('categories', 'pictures', 'directories'));
    }
}