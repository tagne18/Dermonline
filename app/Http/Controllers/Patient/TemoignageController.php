<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TemoignageController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::where('user_id', Auth::id())->get();
        return view('patient.temoignages.index', compact('testimonials'));
    }

    public function create()
    {
        return view('patient.temoignages.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'occupation' => 'nullable|string',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('testimonials', 'public');
        }

        $data['user_id'] = Auth::id();
        $data['approved'] = false;

        Testimonial::create($data);

        return redirect()->route('patient.temoignages.index')->with('success', 'Témoignage soumis pour validation.');
    }
}
