<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Contact\ContactRepository;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public $contactRepository;
    public function __construct(ContactRepository $contactRepository)
    {
        $this->contactRepository=$contactRepository;
    }

    public function index(){
        $data = $this->contactRepository->all();
        return response()->json([
            'result'=>true,
            'message'=>'contact',
            'data'=>$data
        ]);
    }
}
