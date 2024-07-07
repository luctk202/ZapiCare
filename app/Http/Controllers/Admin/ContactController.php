<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Contact\ContactRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ContactController extends Controller
{
    public $contactRepository;

    public function __construct(ContactRepository $contactController)
    {
        $this->contactRepository = $contactController;
    }

    public function index(Request $request)
    {

        $data = $this->contactRepository->all();
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"],
            ['name' => "Danh sách"]
        ];
        return view('admin.content.contact.index')->with(compact('data', 'breadcrumbs'));
    }

    public function create(Request $request)
    {
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"],
            ['link' => route('admin.level.index'), 'name' => "Danh sách"],
            ['name' => 'Tạo mới']
        ];
        return view('admin.content.contact.create')->with(compact('breadcrumbs'));
    }

    public function store(Request $request)
    {
        $data = $request->only('email', 'phone', 'address');
        Cache::forget('contact');
        $this->contactRepository->create($data);
        return redirect()->route('admin.contact.index');
    }

    public function edit($id)
    {
        $data = $this->contactRepository->find($id);
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"],
            ['link' => route('admin.contact.index'), 'name' => "Danh sách"],
            ['name' => 'Sửa']
        ];
        return view('admin.content.contact.edit')->with(compact('data', 'breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        {
            $attr = $this->contactRepository->find($id);
            $data = $request->only(['phone', 'email', 'address']);
            Cache::forget('contact');
            $this->contactRepository->edit($attr, $data);
            return redirect()->route('admin.contact.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $admin = $this->contactRepository->find($id);
        $this->contactRepository->delete($admin);
        Cache::forget('contact');
        return response(['result' => true]);
    }

}
