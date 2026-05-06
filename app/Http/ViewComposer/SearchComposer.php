<?php

namespace App\Http\ViewComposer;
use GuzzleHttp\Psr7\Request;
use Illuminate\View\View;
use App\Models\Clinic;
use App\Models\Specialty;

class SearchComposer
{
    public function compose(View $view) {

        $clinics = Clinic::orderByDesc('id')->get();
        $specialties = Specialty::orderByDesc('id')->get();
        $view->with([
            'clinics' => $clinics,
            'specialties' => $specialties,
        ]);
    }
}