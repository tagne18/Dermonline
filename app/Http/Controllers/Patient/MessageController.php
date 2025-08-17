<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $messages = \App\Models\Message::where('receiver_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->with('sender')
            ->get();
        return view('patient.messages.index', compact('messages'));
    }
}
