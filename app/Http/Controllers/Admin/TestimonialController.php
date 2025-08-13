<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestimonialController extends Controller
{

    public function index()
    {
        $testimonials = Testimonial::latest()->paginate(10);
        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        return view('admin.testimonials.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_name' => 'required|string|max:255',
            'comment' => 'required|string',
            'status' => 'sometimes|in:pending,approved,rejected',
        ]);

        $data = $request->only(['patient_name', 'comment', 'status']);

        if ($request->filled('status') && $request->status === 'approved') {
            $data['approved_at'] = now();
        } else {
             $data['status'] = $request->input('status', 'pending');
        }

        Testimonial::create($data);

        // تم التعديل هنا
        return redirect()->route('admin.testimonials.index')->with('success', trans('AdminSb.testimonial_added_successfully'));
    }

    public function edit(Testimonial $testimonial)
    {
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $request->validate([
            'patient_name' => 'required|string|max:255',
            'comment' => 'required|string',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $data = $request->only(['patient_name', 'comment', 'status']);

        if ($request->status === 'approved' && $testimonial->status !== 'approved') {
            $data['approved_at'] = now();
        } elseif ($request->status !== 'approved') {
            $data['approved_at'] = null;
        }

        $testimonial->update($data);

        // تم التعديل هنا
        return redirect()->route('admin.testimonials.index')->with('success', trans('AdminSb.testimonial_updated_successfully'));
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();
        // تم التعديل هنا
        return redirect()->route('admin.testimonials.index')->with('success', trans('AdminSb.testimonial_deleted_successfully'));
    }

    public function approve(Testimonial $testimonial)
    {
        $testimonial->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);
        // تم التعديل هنا
        return redirect()->back()->with('success', trans('AdminSb.testimonial_approved_successfully'));
    }

    public function reject(Testimonial $testimonial)
    {
        $testimonial->update([
            'status' => 'rejected',
            'approved_at' => null,
        ]);
        // تم التعديل هنا
        return redirect()->back()->with('success', trans('AdminSb.testimonial_rejected_successfully'));
    }
}
