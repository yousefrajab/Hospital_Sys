<?php

namespace App\Repository\LaboratorieEmployee;

use App\Traits\UploadTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Models\LaboratorieEmployee;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreLaboratorieEmployeeRequest;   // يفترض وجودها
use App\Http\Requests\UpdateLaboratorieEmployeeRequest; // يفترض وجودها
use App\Interfaces\LaboratorieEmployee\LaboratorieEmployeeRepositoryInterface;
use Illuminate\Support\Facades\Log; // للمساعدة في التصحيح

class LaboratorieEmployeeRepository implements LaboratorieEmployeeRepositoryInterface
{
    use UploadTrait;

    public function index()
    {
        $laboratorie_employees = LaboratorieEmployee::with('image')->orderBy('created_at', 'desc')->get(); // استخدام get() أفضل من all() وتحميل الصورة
        return view('Dashboard.laboratorie_employee.index', compact('laboratorie_employees'));
    }

    public function store(StoreLaboratorieEmployeeRequest $request)
    {
        DB::beginTransaction();
        try {
            $laboratorie_employee = new LaboratorieEmployee();
            $laboratorie_employee->national_id = $request->national_id;
            $laboratorie_employee->name = $request->name;
            $laboratorie_employee->email = $request->email;
            $laboratorie_employee->password = Hash::make($request->password);
            $laboratorie_employee->phone = $request->phone; // تأكد من وجود هذا الحقل في الفورم والطلب
            $laboratorie_employee->status = $request->status ?? 1; // قراءة الحالة من الطلب أو الافتراضي 1
            $laboratorie_employee->save();

            if ($request->hasFile('photo')) {
                $this->verifyAndStoreImage(
                    $request,
                    'photo',
                    'laboratorieEmployees',
                    'upload_image', // تأكد أن هذا القرص معرف بشكل صحيح
                    $laboratorie_employee->id,
                    LaboratorieEmployee::class // استخدام ::class أفضل
                );
            }

            DB::commit();
            session()->flash('add', 'تم إضافة الموظف بنجاح.');
            return redirect()->route('admin.laboratorie_employee.index');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error storing LaboratorieEmployee: " . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'حدث خطأ أثناء إضافة الموظف: ' . $e->getMessage()]);
        }
    }

    /**
     * إظهار مودال تعديل موظف مختبر معين.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $laboratorie_employee = LaboratorieEmployee::with('image')->find($id);
        if (!$laboratorie_employee) {
            abort(404, 'الموظف غير موجود.');
        }
        // *** تعديل هنا: عرض صفحة التعديل الكاملة ***
        return view('Dashboard.laboratorie_employee.edit', compact('laboratorie_employee'));
    }


    public function update(UpdateLaboratorieEmployeeRequest $request, $id)
    {
        DB::beginTransaction(); // يفضل استخدام transaction هنا أيضًا
        try {
            $laboratorie_employee = LaboratorieEmployee::findOrFail($id);

            // استخدام validated data من FormRequest
            $validatedData = $request->validated();
            $updateData = Arr::except($validatedData, ['photo', 'password', 'password_confirmation']); // استثناء الحقول التي تحتاج معالجة خاصة

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($validatedData['password']);
            }

            $laboratorie_employee->update($updateData);

            if ($request->hasFile('photo')) {
                if ($laboratorie_employee->image) {
                    // تمرير الـ Type الصحيح للحذف
                    $this->Delete_attachment(
                        'upload_image',
                        'laboratorieEmployees/' . $laboratorie_employee->image->filename,
                        $laboratorie_employee->id,
                        LaboratorieEmployee::class // ** مهم هنا **
                    );
                }
                $this->verifyAndStoreImage(
                    $request,
                    'photo',
                    'laboratorieEmployees',
                    'upload_image',
                    $laboratorie_employee->id,
                    LaboratorieEmployee::class
                );
            }
            DB::commit();
            session()->flash('edit', 'تم تعديل بيانات الموظف بنجاح.');
            return redirect()->route('admin.laboratorie_employee.index');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            Log::error("Validation error updating LaboratorieEmployee ID {$id}: " . json_encode($e->errors()));
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error updating LaboratorieEmployee ID {$id}: " . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'حدث خطأ أثناء تعديل الموظف: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $laboratorie_employee = LaboratorieEmployee::findOrFail($id);
            if ($laboratorie_employee->image) {
                $this->Delete_attachment(
                    'upload_image',
                    'laboratorieEmployees/' . $laboratorie_employee->image->filename,
                    $laboratorie_employee->id,
                    LaboratorieEmployee::class // ** مهم هنا **
                );
            }
            $laboratorie_employee->delete(); // استخدام delete على الموديل أفضل
            DB::commit();
            session()->flash('delete', 'تم حذف الموظف بنجاح.');
            return redirect()->route('admin.laboratorie_employee.index');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error deleting LaboratorieEmployee ID {$id}: " . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'حدث خطأ أثناء حذف الموظف: ' . $e->getMessage()]);
        }
    }
}
