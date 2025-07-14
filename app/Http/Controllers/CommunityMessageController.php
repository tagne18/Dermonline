<?php

namespace App\Http\Controllers;

use App\Models\Community\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommunityMessageController extends Controller
{
    public function index()
    {
        $messages = CommunityMessage::with('user')->latest()->take(50)->get()->reverse()->values();

        return response()->json(
            $messages->map(fn ($msg) => [
                'user' => $msg->user->name,
                'content' => $msg->content,
                'created_at' => $msg->created_at->diffForHumans()
            ])
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $msg = CommunityMessage::create([
            'user_id' => Auth::id(),
            'content' => $validated['message']
        ]);

        return response()->json([
            'success' => true,
            'message' => $msg->content
        ]);
    }
}
