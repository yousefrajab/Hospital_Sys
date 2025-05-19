<?php

namespace App\Http\Controllers\Dashboard;

use App\Traits\UploadTrait;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\PharmacyEmployee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StorePharmacyEmployeeRequest;
use App\Http\Requests\UpdatePharmacyEmployeeRequest;

class PharmacyEmployeeController extends Controller
{

    use UploadTrait;
    public function index()
    {
        $pharmacy_employees = PharmacyEmployee::with('image')->orderBy('created_at', 'desc')->get(); // استخدام get() أفضل من all() وتحميل الصورة
        return view('Dashboard.pharmacy_employee.index', compact('pharmacy_employees'));
    }

    public function store(StorePharmacyEmployeeRequest $request)
    {
        DB::beginTransaction();
        try {
            $pharmacy_employee = new PharmacyEmployee();
            $pharmacy_employee->national_id = $request->national_id;
            $pharmacy_employee->name = $request->name;
            $pharmacy_employee->email = $request->email;
            $pharmacy_employee->password = Hash::make($request->password);
            $pharmacy_employee->phone = $request->phone; // تأكد من وجود هذا الحقل في الفورم والطلب
            $pharmacy_employee->status = $request->status ?? 1; // قراءة الحالة من الطلب أو الافتراضي 1
            $pharmacy_employee->save();

            if ($request->hasFile('photo')) {
                $this->verifyAndStoreImage(
                    $request,
                    'photo',
                    'pharmacyEmployees',
                    'upload_image', // تأكد أن هذا القرص معرف بشكل صحيح
                    $pharmacy_employee->id,
                    PharmacyEmployee::class // استخدام ::class أفضل
                );
            }

            DB::commit();
            session()->flash('add', 'تم إضافة الموظف بنجاح.');
            return redirect()->route('admin.pharmacy_employee.index');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error storing PharmacyEmployee: " . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'حدث خطأ أثناء إضافة الموظف: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $pharmacy_employee = PharmacyEmployee::with('image')->find($id);
        if (!$pharmacy_employee) {
            abort(404, 'الموظف غير موجود.');
        }
        // *** تعديل هنا: عرض صفحة التعديل الكاملة ***
        return view('Dashboard.pharmacy_employee.edit', compact('pharmacy_employee'));
    }


    public function update(UpdatePharmacyEmployeeRequest $request, $id)
    {
        DB::beginTransaction(); // يفضل استخدام transaction هنا أيضًا
        try {
            $pharmacy_employee = PharmacyEmployee::findOrFail($id);

            // استخدام validated data من FormRequest
            $validatedData = $request->validated();
            $updateData = Arr::except($validatedData, ['photo', 'password', 'password_confirmation']); // استثناء الحقول التي تحتاج معالجة خاصة

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($validatedData['password']);
            }

            $pharmacy_employee->update($updateData);

            if ($request->hasFile('photo')) {
                if ($pharmacy_employee->image) {
                    // تمرير الـ Type الصحيح للحذف
                    $this->Delete_attachment(
                        'upload_image',
                        'pharmacyEmployees/' . $pharmacy_employee->image->filename,
                        $pharmacy_employee->id,
                        PharmacyEmployee::class // ** مهم هنا **
                    );
                }
                $this->verifyAndStoreImage(
                    $request,
                    'photo',
                    'pharmacyEmployees',
                    'upload_image',
                    $pharmacy_employee->id,
                    PharmacyEmployee::class
                );
            }
            DB::commit();
            session()->flash('edit', 'تم تعديل بيانات الموظف بنجاح.');
            return redirect()->route('admin.pharmacy_employee.index');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            Log::error("Validation error updating PharmacyEmployee ID {$id}: " . json_encode($e->errors()));
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error updating PharmacyEmployee ID {$id}: " . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'حدث خطأ أثناء تعديل الموظف: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $pharmacy_employee = PharmacyEmployee::findOrFail($id);
            if ($pharmacy_employee->image) {
                $this->Delete_attachment(
                    'upload_image',
                    'pharmacyEmployees/' . $pharmacy_employee->image->filename,
                    $pharmacy_employee->id,
                    PharmacyEmployee::class // ** مهم هنا **
                );
            }
            $pharmacy_employee->delete(); // استخدام delete على الموديل أفضل
            DB::commit();
            session()->flash('delete', 'تم حذف الموظف بنجاح.');
            return redirect()->route('admin.pharmacy_employee.index');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error deleting PharmacyEmployee ID {$id}: " . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'حدث خطأ أثناء حذف الموظف: ' . $e->getMessage()]);
        }
    }
}
