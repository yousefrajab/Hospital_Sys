<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientProfileController extends Controller
{
    public function show() // تغيير اسم الدالة ليكون أوضح
    {
        $patient = Auth::guard('patient')->user();
        if (!$patient) {
            abort(404);
        }
        // // تحميل العلاقات الأساسية فقط
        // $doctor->load(['section', 'image']);
        // return view('Dashboard.Doctors.profile.show', compact('admin'));
    }


    public function edit()
    {
        $patient = Auth::guard('patient')->user();
        if (!$patient) {
            abort(404);
        }
        // // يمكنك جلب بيانات إضافية هنا إذا لزم الأمر (مثل الأقسام)
        // return view('Dashboard.Doctors.profile.edit', compact('doctor'));
    }

    public function update(Request $request)
    {
        $patient = Auth::guard('patient')->user();
        if (!$patient) {
            abort(404);
        }
        // --- هنا ستضع منطق التحقق والتحديث المشابه لما في DoctorRepository ---
        // --- لكن بشكل مبسط وموجه للطبيب نفسه ---
        // $request->validate([...]);
        // $doctor->update([...]);
        // // تحديث الترجمة
        // $doctor->name = $request->name;
        // $doctor->save();
        // // تحديث الصورة إذا تم رفعها
        // // تحديث كلمة المرور إذا تم إدخالها

        // return redirect()->route('doctor.profile.show')->with('success', 'تم تحديث الملف الشخصي بنجاح.');
    }
}
