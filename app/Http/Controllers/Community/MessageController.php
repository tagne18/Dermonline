<?php

namespace App\Http\Controllers\Community;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Community\Message;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::with('user')->latest()->take(30)->get()->reverse()->values();

        return response()->json(
            $messages->map(fn ($msg) => [
                'user' => $msg->user->name,
                'content' => $msg->content,
                'created_at' => $msg->created_at->diffForHumans(),
            ])
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        Message::create([
            'user_id' => Auth::id(),
            'content' => $request->message,
        ]);

        return response()->json(['success' => true]);
    }
}

