<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::where('user_id', auth()->id())->latest()->get();
        return view('medecin.messages.index', compact('messages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'contenu' => 'required|string',
        ]);

        Message::create([
            'user_id' => auth()->id(),
            'contenu' => $request->contenu,
            'public' => true,
        ]);

        return back()->with('success', 'Message envoyé à la communauté.');
    }
}
