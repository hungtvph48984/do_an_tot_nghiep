<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    public function showForm()
    {
        return view('clients.contacts.contact');
    }

    public function submitForm(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'phone'   => 'required|string|max:20',
            'message' => 'required|string',
        ]);

        Contact::create([
            'user_id' => auth()->id(), // gắn với user hiện tại
            'name'    => $request->name,
            'email'   => $request->email,
            'phone'   => $request->phone,
            'message' => $request->message,
        ]);

        return redirect()->back()->with('success', 'Cảm ơn bạn đã phản hồi! Chúng tôi sẽ liên hệ lại sớm.');
    }

    public function myContacts()
    {
        $contacts = Contact::where('user_id', auth()->id())->latest()->get();

        // Nếu có contact nào đã được reply thì báo cho user
        if ($contacts->whereNotNull('reply')->count() > 0) {
            session()->flash('success', 'Bạn có phản hồi mới từ admin!');
        }

        return view('clients.contacts.my', compact('contacts'));
    }

    public function show($id)
    {
        $contact = Contact::where('id', $id)
            ->where('email', auth()->user()->email) // chỉ cho phép xem liên hệ của chính mình
            ->firstOrFail();

        return view('clients.contacts.show', compact('contact'));
    }



}
