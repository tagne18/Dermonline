<?php

namespace App\Http\Controllers;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::with('user')->latest()->take(50)->get()->reverse()->values();

        return response()->json(
            $messages->map(fn($msg) => [
                'user' => $msg->user->name ?? 'Utilisateur inconnu',
                'content' => $msg->content
            ])
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        Message::create([
            'user_id' => Auth::id(),
            'content' => $request->message
        ]);

        return response()->json(['status' => 'ok']);
    }
}
