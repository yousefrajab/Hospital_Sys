<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ServiceManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:doctor');
    }

    public function index()
    {
        $doctor = Auth::user();

        $doctorServices = $doctor->services()->orderBy('created_at', 'desc')->get();
        return view('Dashboard.doctor.services_management.index', compact('doctorServices', 'doctor'));
    }

    public function create()
    {
        return view('Dashboard.doctor.services_management.create');
    }

    public function store(Request $request)
    {
        $doctor = Auth::user();

        $validatedData = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
        ]);
        $existingService = Service::where('doctor_id', $doctor->id)
            ->whereTranslation('name', $validatedData['name'])
            ->first();

        if ($existingService) {
            return redirect()->back()
                ->withErrors(['name' => 'لقد قمت بإضافة خدمة بهذا الاسم من قبل.'])
                ->withInput();
        }

        DB::beginTransaction();
        try {
            Service::create([
                'name' => $validatedData['name'],
                'price' => $validatedData['price'],
                'description' => $validatedData['description'],
                'status' => $validatedData['status'],
                'doctor_id' => $doctor->id,
            ]);

            DB::commit();
            return redirect()->route('doctor.services_management.index')
                ->with('success', 'تم إنشاء الخدمة بنجاح باسمك.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withErrors(['error' => 'فشل إنشاء الخدمة: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function edit(Service $service)
    {
        $doctor = Auth::user();
        if ($service->doctor_id != $doctor->id) {
            return redirect()->route('doctor.services_management.index')->with('error', 'غير مصرح لك بتعديل هذه الخدمة.');
        }
        return view('Dashboard.doctor.services_management.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $doctor = Auth::user();
        if ($service->doctor_id != $doctor->id) {
            return redirect()->route('doctor.services_management.index')->with('error', 'غير مصرح لك بتحديث هذه الخدمة.');
        }

        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        $existingService = Service::where('doctor_id', $doctor->id)
            ->whereTranslation('name', $validatedData['name'])
            ->where('id', '!=', $service->id)
            ->first();
        if ($existingService) {
            return redirect()->back()
                ->withErrors(['name' => 'لديك خدمة أخرى بهذا الاسم.'])
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $service->update([
                'name' => $validatedData['name'],
                'price' => $validatedData['price'],
                'description' => $validatedData['description'],
                'status' => $validatedData['status'],
            ]);

            DB::commit();
            return redirect()->route('doctor.services_management.index')->with('success', 'تم تحديث الخدمة بنجاح.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['error' => 'فشل تحديث الخدمة: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy(Service $service)
    {
        $doctor = Auth::user();
        if ($service->doctor_id != $doctor->id) {
            return redirect()->route('doctor.services_management.index')->with('error', 'غير مصرح لك بحذف هذه الخدمة.');
        }

        try {
            $service->delete();
            return redirect()->route('doctor.services_management.index')->with('success', 'تم حذف الخدمة بنجاح.');
        } catch (\Exception $e) {
            return redirect()->route('doctor.services_management.index')->with('error', 'فشل حذف الخدمة: ' . $e->getMessage());
        }
    }
}
