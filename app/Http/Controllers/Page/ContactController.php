<?php

namespace App\Http\Controllers\Page;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Models\Contact;

class ContactController extends Controller
{
    //
    public function __construct()
    {
        view()->share([]);
    }

    public function sendContact(ContactRequest $request)
    {
        $params = $request->except(['_token', 'submit']);

        \DB::beginTransaction();
        try {
            Contact::create($params);
            \DB::commit();
            return redirect()->back()->with('success', 'Gửi liên hệ thành công chúng tôi sẽ phản hồi cho bạn qua mail đã đăng ký');
        } catch (\Exception $exception) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Đã xảy ra lỗi không thể gửi liên hệ');
        }
    }
}
