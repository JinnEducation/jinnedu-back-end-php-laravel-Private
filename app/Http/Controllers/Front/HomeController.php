<?php

namespace App\Http\Controllers\Front;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {

         $tutors = User::query()
        ->where('type', 2) 
        ->with([
            'abouts.country:id,name',              
            'descriptions.specialization:id,name', 
        ])
        ->select('id','name','slug','avatar')
        ->latest('id')      
        ->limit(12)
        ->get();

    return view('front.home', compact('tutors'));
    }
}
