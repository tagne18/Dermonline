<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NewsletterSubscriber;
use Illuminate\Support\Facades\Mail;

class NewsletterController extends Controller
{
    public function index()
    {
        $subscribers = NewsletterSubscriber::latest()->paginate(20);
        return view('admin.newsletters.index', compact('subscribers'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $emails = NewsletterSubscriber::pluck('email')->toArray();
        $subject = $request->subject;
        $content = $request->content;

        foreach ($emails as $email) {
            Mail::raw($content, function ($message) use ($email, $subject) {
                $message->to($email)->subject($subject);
            });
        }

        return back()->with('success', 'Email envoyé à tous les abonnés !');
    }
}
