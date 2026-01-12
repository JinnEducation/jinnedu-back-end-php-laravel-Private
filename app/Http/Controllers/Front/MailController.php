<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Mail\ContactUs;
use App\Mail\SendMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function send()
    {
        Mail::to('bou@gmail.com')->send(new SendMail);

        return 'Done';
    }

    public function contact()
    {

        return view('front.contact');
    }

    public function contact_data(Request $request)
    {

        //  dd($request->all());

        $request->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required',
            'mobile' => 'required',
            'message' => 'required',

        ]);

        $data = $request->except('_token');
        Mail::to('contactus@gmail.com')->send(new ContactUs($data));

    }
}
