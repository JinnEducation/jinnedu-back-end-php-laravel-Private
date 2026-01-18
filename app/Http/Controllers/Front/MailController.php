<?php

namespace App\Http\Controllers\Front;

use App\Mail\SendMail;
use App\Mail\ContactUs;
use Illuminate\Http\Request;
use App\Models\ContactMessage;
use App\Http\Controllers\Controller;
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

        $validated = $request->validate([
            'f_name' => 'required',
            'l_name' => 'required',
            'email' => 'required',
            'mobile' => 'required',
            'message' => 'required',
        ]);

        ContactMessage::create([
            'f_name' => $validated['f_name'],
            'l_name'  => $validated['l_name'],
            'email'      => $validated['email'],
            'mobile'     => $validated['mobile'],
            'message'    => $validated['message'],
        ]);

        $data = $request->except('_token');
        Mail::to('contactus@gmail.com')->send(new ContactUs($data));

         return back()->with('success', __('site.Message sent successfully'));

    }
}
