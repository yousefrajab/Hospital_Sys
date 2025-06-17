<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // سيبقى لاستخدامه في المستقبل إذا أردت تتبع من أنشأ التعليق من الأدمن مثلاً

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $testimonials = Testimonial::latest()->paginate(10);
        return view('admin.testimonials.index', compact('testimonials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.testimonials.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'patient_name' => 'required|string|max:255',
            'comment' => 'required|string',
            'status' => 'sometimes|in:pending,approved,rejected', // يمكن تحديد الحالة مباشرة عند الإنشاء
        ]);

        $data = $request->only(['patient_name', 'comment', 'status']);

        if ($request->filled('status') && $request->status === 'approved') {
            $data['approved_at'] = now();
        } else {
            // إذا لم يتم تحديد الحالة، أو كانت pending/rejected
            // تأكد أن status يتم تعيينه بشكل صحيح إذا لم يأتِ من الفورم
            // الـ default في الـ migration سيتكفل بحالة 'pending' إذا لم يتم إرسال 'status'
             $data['status'] = $request->input('status', 'pending');
        }

        Testimonial::create($data);

        return redirect()->route('admin.testimonials.index')->with('success', 'تم إضافة التعليق بنجاح.');
    }

    /**
     * Display the specified resource.
     * (عادة لا نحتاج لهذه الدالة في إدارة التعليقات من لوحة التحكم)
     */
    // public function show(Testimonial $testimonial)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Testimonial $testimonial)
    {
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    /**
     * Update the specified resource in storage.
     */
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
        // إذا كانت الحالة approved ولم تتغير، لا نغير approved_at، ستبقى القيمة القديمة

        $testimonial->update($data);

        return redirect()->route('admin.testimonials.index')->with('success', 'تم تحديث التعليق بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();
        return redirect()->route('admin.testimonials.index')->with('success', 'تم حذف التعليق بنجاح.');
    }

    /**
     * Approve the specified testimonial.
     */
    public function approve(Testimonial $testimonial)
    {
        $testimonial->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);
        return redirect()->back()->with('success', 'تمت الموافقة على التعليق.');
    }

    /**
     * Reject the specified testimonial.
     */
    public function reject(Testimonial $testimonial)
    {
        $testimonial->update([
            'status' => 'rejected',
            'approved_at' => null,
        ]);
        return redirect()->back()->with('success', 'تم رفض التعليق.');
    }
}
