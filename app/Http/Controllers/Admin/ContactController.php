<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Contact;

class ContactController extends Controller
{
    //
    public function __construct()
    {
        view()->share([
            'contact_active' => 'active',
        ]);
    }

    public function index()
    {
        $contacts = Contact::orderBy('id', 'DESC')->paginate(10);
        $viewData = [
            'contacts' => $contacts
        ];

        return view('admin.contact.index', $viewData);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        //
        $contact = Contact::find($id);
        if (!$contact) {
            return redirect()->back()->with('error', 'Dữ liệu không tồn tại');
        }

        try {
            $contact->delete();
            return redirect()->back()->with('success', 'Xóa thành công');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi không thể xóa dữ liệu');
        }
    }
}
