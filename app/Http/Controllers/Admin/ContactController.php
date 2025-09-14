<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactReplyMail;

class ContactController extends Controller
{
    // Danh sách liên hệ
    public function index()
    {
        $contacts = Contact::latest()->paginate(10);
        return view('admins.contacts.index', compact('contacts'));
    }

    // Xem chi tiết liên hệ
    public function show($id)
    {
        $contact = Contact::findOrFail($id);
        return view('admins.contacts.show', compact('contact'));
    }

    // Phản hồi liên hệ
    public function reply(Request $request, $id)
    {
        $request->validate([
            'reply' => 'required|string',
        ]);

        $contact = Contact::findOrFail($id);
        $contact->reply = $request->reply;
        $contact->save();

        // Gửi email phản hồi
        Mail::to($contact->email)->send(new ContactReplyMail($contact));

        return redirect()->route('admin.contacts.index', $contact->id)
            ->with('success', 'Phản hồi đã được gửi tới khách hàng!');
    }

}
