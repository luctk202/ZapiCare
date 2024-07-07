<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\ContactForm\ContactFormRepository;
use Illuminate\Http\Request;

class ContactFormController extends Controller
{
    public  $contactFormRepository;

    public function __construct(ContactFormRepository $contactFormRepository)
    {
        $this->contactFormRepository=$contactFormRepository;
    }
    public function index(Request $request){
        $where = [];
        if (!empty($request->name)) {
            $where['name'] = ['name', 'like', $request->name];
        }
        if (!empty($request->phone)) {
            $where['phone'] = ['phone', 'like', $request->phone];
        }
        if (!empty($request->address)) {
            $where['address'] = ['address', 'like', $request->address];
        }
//        if (!empty($request->content)) {
//            $where['content'] = ['content', 'like', $request->content];
//        }
        $data=$this->contactFormRepository->paginate($where, ['id' => 'DESC']);
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"],
            ['name' => "Thông tin liên hệ"]
        ];
        return view('admin.content.contact_form.index')->with(compact('data', 'breadcrumbs'));
    }
}
