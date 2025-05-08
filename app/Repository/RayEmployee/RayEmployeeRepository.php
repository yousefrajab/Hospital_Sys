<?php

namespace App\Repository\RayEmployee;

use App\Models\RayEmployee;
use App\Traits\UploadTrait; // استيراد الـ Trait
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB; // استيراد DB
use Illuminate\Support\Facades\Hash;
use App\Interfaces\RayEmployee\RayEmployeeRepositoryInterface;
// استيراد ملفات الطلبات (Requests) التي سننشئها لاحقًا
use App\Http\Requests\RayEmployee\StoreRayEmployeeRequest;
use App\Http\Requests\RayEmployee\UpdateRayEmployeeRequest;

class RayEmployeeRepository implements RayEmployeeRepositoryInterface
{
    use UploadTrait; // استخدام الـ Trait

    public function index()
    {
        $ray_employees = RayEmployee::orderBy('created_at', 'desc')->get(); // جلب مرتب
        return view('Dashboard.ray_employee.index', compact('ray_employees'));
    }

    // تم تغيير $request ليأخذ النوع الصحيح
    public function store(StoreRayEmployeeRequest $request)
    {
        DB::beginTransaction(); // بدء الـ Transaction
        try {
            $ray_employee = new RayEmployee();
            $ray_employee->national_id = $request->national_id;
            $ray_employee->name = $request->name;
            $ray_employee->email = $request->email;
            $ray_employee->password = Hash::make($request->password);
            $ray_employee->phone = $request->phone;     // إضافة حقل الهاتف
            $ray_employee->status = $request->status;   // إضافة حقل الحالة
            $ray_employee->save();

            // رفع الصورة إذا كانت موجودة
            if ($request->hasFile('photo')) {
                $this->verifyAndStoreImage(
                    $request,
                    'photo',
                    'rayEmployees', // اسم المجلد لتخزين صور موظفي الأشعة
                    'upload_image',          // اسم القرص (Disk)
                    $ray_employee->id,
                    'App\Models\RayEmployee' // نوع الموديل
                );
            }

            DB::commit(); // تأكيد الـ Transaction
            session()->flash('add', 'تمت إضافة موظف الأشعة بنجاح.');
            return redirect()->route('admin.ray_employee.index'); // توجيه إلى صفحة القائمة

        } catch (\Exception $e) {
            DB::rollback(); // تراجع في حالة الخطأ
            return redirect()->back()->withInput()->withErrors(['error' => 'حدث خطأ أثناء إضافة الموظف: ' . $e->getMessage()]);
        }
    }


    public function edit($id)
    {
        $ray_employee = RayEmployee::with('image')->find($id);
        if (!$ray_employee) {
            abort(404, 'الموظف غير موجود.');
        }
        // *** تعديل هنا: عرض صفحة التعديل الكاملة ***
        return view('Dashboard.ray_employee.edit', compact('ray_employee'));
    }

    // تم تغيير $request ليأخذ النوع الصحيح
    public function update(UpdateRayEmployeeRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $ray_employee = RayEmployee::findOrFail($id);

            // استثناء 'photo' من $input لأننا سنتعامل معها بشكل منفصل
            $input = $request->except('photo', '_token', '_method');

            if (!empty($input['password'])) {
                $input['password'] = Hash::make($input['password']);
            } else {
                $input = Arr::except($input, ['password']);
            }

            $ray_employee->update($input);

            // تحديث الصورة إذا تم رفع صورة جديدة
            if ($request->hasFile('photo')) {
                // حذف الصورة القديمة إذا كانت موجودة
                if ($ray_employee->image) {
                    $this->Delete_attachment(
                        'upload_image',
                        'rayEmployees/' . $ray_employee->image->filename,
                        $ray_employee->id,
                        'App\Models\RayEmployee'
                    );
                }
                $this->verifyAndStoreImage(
                    $request,
                    'photo',
                    'rayEmployees', // اسم المجلد لتخزين صور موظفي الأشعة
                    'upload_image',          // اسم القرص (Disk)
                    $ray_employee->id,
                    'App\Models\RayEmployee' // نوع الموديل
                );
            }
            DB::commit();
            session()->flash('edit', 'تم تعديل بيانات موظف الأشعة بنجاح.');
            return redirect()->route('admin.ray_employee.index'); // توجيه إلى صفحة القائمة

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput()->withErrors(['error' => 'حدث خطأ أثناء تعديل الموظف: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $ray_employee = RayEmployee::findOrFail($id);
            // حذف الصورة المرتبطة بالموظف إذا كانت موجودة
            if ($ray_employee->image) {
                $this->Delete_attachment(
                    'upload_image',
                    'rayEmployees/' . $ray_employee->image->filename,
                    $ray_employee->id,
                    'App\Models\RayEmployee'
                );
            }
            $ray_employee->delete(); // استخدام delete() على الموديل
            DB::commit();
            session()->flash('delete', 'تم حذف موظف الأشعة بنجاح.');
            return redirect()->route('admin.ray_employee.index'); // توجيه إلى صفحة القائمة

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['error' => 'حدث خطأ أثناء حذف الموظف: ' . $e->getMessage()]);
        }
    }
}
