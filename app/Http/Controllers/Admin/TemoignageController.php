<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TemoignageController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::with('user')->get();
        return view('admin.temoignages.index', compact('testimonials'));
    }

    public function approve($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->approved = true;
        $testimonial->save();
        return redirect()->route('admin.testimonials')->with('success', 'Témoignage approuvé.');
    }

    public function destroy($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        if ($testimonial->image) {
            \Storage::disk('public')->delete($testimonial->image);
        }
        $testimonial->delete();
        return redirect()->route('admin.testimonials')->with('success', 'Témoignage supprimé.');
    }
}
