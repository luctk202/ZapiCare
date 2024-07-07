<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Repositories\Blog\BlogRepository;
use App\Repositories\BlogCategory\BlogCategoryRepository;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public $blogRepository;

    public function __construct(BlogRepository $blogRepository)
    {
        $this->blogRepository = $blogRepository;
    }


    public function index(Request $request){
        $where = [
            'status' => $this->blogRepository::STATUS_ACTIVE
        ];
        /*if (!empty($request->blog_category_id)) {
            $where['blog_category_id'] = $request->blog_category_id;
        }*/
        $data = $this->blogRepository->get($where, ['created_at' => 'DESC'],['id', 'slug', 'title', 'image', 'description']);
        return response([
            'result' => true,
            'data' => $data
        ]);
    }

    public function show($slug){
        $where = [
            'slug' => $slug
        ];
        $data = $this->blogRepository->first($where, ['created_at' => 'DESC']);
        return response([
            'result' => true,
            'data' => $data
        ]);
    }



}
