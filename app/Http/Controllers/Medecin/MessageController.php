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

    /**
     * Envoi d'un message privé médecin → patient (avec pièce jointe possible)
     */
    public function sendToPatient(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required_without:attachment|string|nullable',
            'attachment' => 'nullable|file|max:5120', // max 5Mo
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('messages', 'public');
        }

        $message = \App\Models\Message::create([
            'user_id' => auth()->id(),
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'content' => $request->content,
            'attachment' => $attachmentPath,
        ]);

        // Notifier le patient (notification personnalisée à créer si besoin)
        $receiver = \App\Models\User::find($request->receiver_id);
        if ($receiver) {
            // $receiver->notify(new ...); // Notification à implémenter
        }

        return back()->with('success', 'Message envoyé au patient.');
    }
}
