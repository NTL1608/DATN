<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Slide;
use App\Models\User;

class HomeController extends Controller
{
    //
    public function index()
    {
        $slides = Slide::where(['active' => 1])->orderBy('sort')->get();
        $users = User::with(['specialty', 'ratings'])->where(['type' => User::TYPE_DOCTOR, 'status' =>  1])->limit('20')->orderByDesc('id')->get();
        $positions = User::POSITIONS;

        $viewData = [
            'slides' => $slides,
            'users' => $users,
            'positions' => $positions,
        ];

        return view('page.home.index', $viewData);
    }

    public function contact()
    {
        return view('page.contact.index');
    }

    public function about()
    {
        return view('page.about.index');
    }

}
