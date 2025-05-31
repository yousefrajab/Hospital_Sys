<?php

namespace App\Repository\Services;

use App\Models\Doctor;
use App\Models\Service;
use Illuminate\Support\Facades\DB;

class SingleServiceRepository implements \App\Interfaces\Services\SingleServiceRepositoryInterface
{

    public function index()
    {
        // تأكد أن علاقة 'doctor' (مفرد) مُعرفة في موديل Service
        // وأن علاقة 'translations' مُعرفة إذا كنت تستخدمها (مثلاً مع astrotomic/laravel-translatable)
        $services = Service::with(['doctor'])->orderBy('created_at', 'desc')->get();
        $doctors = Doctor::where('status', 1)->orderByTranslation('name')->get(); // جلب الأطباء النشطين وترتيبهم بالاسم المترجم
        return view('Dashboard.Services.Single Service.index', compact('services', 'doctors'));
    }

    public function create() // هذا الميثود قد لا يُستخدم إذا كانت الإضافة تتم عبر مودال من صفحة index
    {
        $doctors = Doctor::where('status', 1)->orderByTranslation('name')->get();
        return view('Dashboard.Services.Single Service.add', compact('doctors')); // افترض أن لديك هذا الـ view
    }

    public function store($request)
    {
        // قم بإضافة Validation هنا للـ request
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'doctor_id' => 'nullable|exists:doctors,id', // تأكد أن الطبيب موجود إذا تم إرساله
            'status' => 'required|boolean',
        ]);

        DB::beginTransaction();
        try {
            $SingleService = new Service();
            $SingleService->price = $request->price;
            // $SingleService->description = $request->description; // الوصف يجب أن يُحفظ مع الترجمة إذا كان مترجماً
            $SingleService->status = $request->status; // احصل على الحالة من الطلب
            $SingleService->doctor_id = $request->doctor_id;
            $SingleService->save(); // الحفظ الأول للبيانات الأساسية

            // حفظ الترجمات
            $SingleService->name = $request->name; // يفترض أن 'name' هو translatedAttribute
            $SingleService->description = $request->description; // إذا كان الوصف مترجماً أيضاً
            $SingleService->save(); // الحفظ الثاني لتحديث الترجمات

            DB::commit();
            session()->flash('add', trans('messages.success')); // استخدام رسالة نجاح عامة
            return redirect()->route('admin.Service.index');

        } catch (\Exception $e) {
            DB::rollback();
            // Log the error: Log::error($e->getMessage());
            return redirect()->back()->withErrors(['error' => trans('messages.fail') . ': ' . $e->getMessage()])->withInput();
        }
    }

    public function edit($id) // هذا الميثود قد لا يُستخدم إذا كانت التعديل يتم عبر مودال من صفحة index
    {
        $service = Service::with(['doctor'])->findOrFail($id);
        $doctors = Doctor::where('status', 1)->orderByTranslation('name')->get();
        return view('Dashboard.Services.Single Service.edit', compact('service', 'doctors')); // افترض أن لديك هذا الـ view
    }

    public function update($request) // كان يجب أن يستقبل $request, $id أو يستخدم route model binding
    {
        // قم بإضافة Validation هنا للـ request
        $validatedData = $request->validate([
           'id' => 'required|exists:services,id', // للتأكد من أن الـ ID المُرسل موجود
            'name' => [
                'required',
                'string',
                'max:255',
                // Rule::unique('service_translations', 'name')->ignore($serviceId, 'service_id') // For single locale in service_translations table
                // If 'name' is directly on 'services' table (not translatable or only one language stored directly)
                // Rule::unique('services', 'name')->ignore($serviceId)

                // *** For astrotomic/laravel-translatable, unique validation is more complex. ***
                // You often need a custom rule or manual check because the 'name' is in a separate table.
                // Let's do a manual check for simplicity here for the current locale:
            ],
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'doctor_id' => 'nullable|exists:doctors,id',
            'status' => 'required|boolean',
        ]);


        DB::beginTransaction();
        try {
            $SingleService = Service::findOrFail($validatedData['id']); // استخدام $request->id أو $validatedData['id']
            $SingleService->price = $validatedData['price'];
            // $SingleService->description = $validatedData['description'];
            $SingleService->status = $validatedData['status'];
            $SingleService->doctor_id = $validatedData['doctor_id'];
            $SingleService->save();

            // تحديث الترجمات
            $SingleService->name = $validatedData['name'];
            $SingleService->description = $validatedData['description'];
            $SingleService->save();

            DB::commit();
            session()->flash('edit', trans('messages.update')); // استخدام رسالة نجاح عامة
            return redirect()->route('admin.Service.index');

        } catch (\Exception $e) {
            DB::rollback();
            // Log::error($e->getMessage());
            return redirect()->back()->withErrors(['error' => trans('messages.fail') . ': ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy($request) // يجب أن يكون $request إما ID أو كائن Request يحتوي على ID
    {
        try {
            //  تأكد من أن $request->id موجود وصحيح
            $serviceId = $request->id ?? ($request instanceof \Illuminate\Http\Request ? $request->input('id') : null);
            if (!$serviceId) {
                 throw new \Exception('Service ID not provided for deletion.');
            }
            Service::destroy($serviceId);
            session()->flash('delete', trans('messages.delete'));
            return redirect()->route('admin.Service.index');
        } catch (\Exception $e) {
            // Log::error($e->getMessage());
            return redirect()->back()->withErrors(['error' => trans('messages.fail') . ': ' . $e->getMessage()]);
        }
    }
}
