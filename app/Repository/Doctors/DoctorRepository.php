<?php

namespace App\Repository\Doctors;

use App\Models\Image;
use App\Models\Doctor;
use App\Models\Section;
use App\Models\Appointment;
use App\Models\DoctorBreak;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\DoctorWorkingDay;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use App\Http\Requests\StoreDoctorsRequest;
use App\Http\Requests\UpdateDoctorsRequest;
use App\Interfaces\Doctors\DoctorRepositoryInterface;

class DoctorRepository implements DoctorRepositoryInterface
{
    use UploadTrait;

    public function index()
    {
        $doctors = Doctor::with(['section', 'image', 'workingDays'])
            ->orderBy('created_at', 'desc') // ترتيب حسب الأحدث مثلاً
            ->get();
        return view('Dashboard.Doctors.index', compact('doctors'));
    }

    public function create()
    {
        $sections = Section::all();
        $appointments = Appointment::all();
        return view('Dashboard.Doctors.add', compact('sections', 'appointments'));
    }

    public function store(StoreDoctorsRequest $request)
    {
        DB::beginTransaction();

        try {
            $doctors = new Doctor();
            $doctors->national_id = $request->national_id;
            $doctors->email = $request->email;
            $doctors->password = Hash::make($request->password);
            $doctors->section_id = $request->section_id;
            $doctors->phone = $request->phone;
            $doctors->status = 1;
            $doctors->number_of_statements = $request->number_of_statements;
            $doctors->save();

            // store translation
            $doctors->name = $request->name;
            $doctors->save();

            // insert pivot table
            $doctors->doctorappointments()->attach($request->appointments);

            // Upload img
            $this->verifyAndStoreImage($request, 'photo', 'doctors', 'upload_image', $doctors->id, 'App\Models\Doctor');

            DB::commit();
            session()->flash('add');
            return redirect()->route('admin.Doctors.index');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function update(UpdateDoctorsRequest $request)
    {
        DB::beginTransaction();

        try {
            $doctor = Doctor::findOrFail($request->id);

            $data = [
                'national_id' => $request->national_id,
                'email' => $request->email,
                'section_id' => $request->section_id,
                'phone' => $request->phone,
                'number_of_statements' => $request->number_of_statements
            ];

            if ($request->filled('password')) {
                $data['password'] = bcrypt($request->password);
            }

            $doctor->update($data);

            // Update translations
            $doctor->name = $request->name;
            $doctor->save();

            // update pivot table
            $doctor->doctorappointments()->sync($request->appointments);

            // update photo
            if ($request->has('photo')) {
                if ($doctor->image) {
                    $old_img = $doctor->image->filename;
                    $this->Delete_attachment('upload_image', 'doctors/' . $old_img, $request->id, 'App\Models\Doctor');
                }
                $this->verifyAndStoreImage($request, 'photo', 'doctors', 'upload_image', $request->id, 'App\Models\Doctor');
            }

            DB::commit();
            session()->flash('edit');
            return redirect()->route('admin.Doctors.index');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy($request)
    {
        if ($request->page_id == 1) {

            if ($request->filename) {

                $this->Delete_attachment('upload_image', 'doctors/' . $request->filename, $request->id, $request->filename);
            }
            Doctor::destroy($request->id);
            session()->flash('delete');
            return redirect()->route('admin.Doctors.index');
        }


        //---------------------------------------------------------------

        else {

            // delete selector doctor
            $delete_select_id = explode(",", $request->delete_select_id);
            foreach ($delete_select_id as $ids_doctors) {
                $doctor = Doctor::findorfail($ids_doctors);
                if ($doctor->image) {
                    $this->Delete_attachment('upload_image', 'doctors/' . $doctor->image->filename, $ids_doctors, $doctor->image->filename);
                }
            }

            Doctor::destroy($delete_select_id);
            session()->flash('delete');
            return redirect()->route('admin.Doctors.index');
        }
    }


    public function edit($id)
    {
        $sections = Section::all();
        $appointments = Appointment::all(); // ملاحظة: هل تحتاج حقاً Appointments هنا؟ أم أنها لـ doctorappointments القديمة؟

        // جلب الطبيب مع علاقات القسم والصورة وأيام العمل (والاستراحات داخل أيام العمل)
        $doctor = Doctor::with(['section', 'image', 'workingDays.breaks']) // *** تم تحديث with هنا ***
            ->findOrFail($id);

        // *** تحضير بيانات أيام العمل للعرض في الفورم (اختياري لكن مفيد) ***
        // إنشاء مصفوفة بأيام الأسبوع لسهولة الوصول في الـ view
        $daysOfWeek = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        // إنشاء مصفوفة تجمع بيانات يوم العمل لكل يوم في الأسبوع
        $workingHoursData = [];
        foreach ($daysOfWeek as $day) {
            // البحث عن سجل يوم العمل المطابق لهذا اليوم (مع تجاهل حالة الأحرف)
            $workingDay = $doctor->workingDays->first(function ($item) use ($day) {
                return strcasecmp($item->day, $day) === 0;
            });

            $workingHoursData[$day] = [
                'exists' => (bool)$workingDay, // هل تم تحديد يوم عمل لهذا اليوم؟
                'active' => $workingDay?->active ?? false, // هل هو نشط؟
                'start_time' => $workingDay?->start_time ?? '', // وقت البدء
                'end_time' => $workingDay?->end_time ?? '',   // وقت الانتهاء
                'appointment_duration' => $workingDay?->appointment_duration ?? 30, // المدة الافتراضية 30
                'working_day_id' => $workingDay?->id ?? null, // ID سجل يوم العمل (مفيد للاستراحات)
                'breaks' => $workingDay?->breaks ?? collect() // قائمة الاستراحات
            ];
        }
        return view('Dashboard.Doctors.edit', compact(
            'sections',
            'appointments', // إعادة النظر إذا كنت تحتاجها
            'doctor',
            'daysOfWeek', // *** تمرير مصفوفة أيام الأسبوع ***
            'workingHoursData' // *** تمرير بيانات ساعات العمل المحضرة ***
        ));
    }

    public function updateWorkingHours(Request $request, $doctorId)
    {
        $doctor = Doctor::findOrFail($doctorId);
        // الحصول على البيانات المرسلة من الفورم (قد تكون فارغة لبعض الأيام)
        $submittedDaysData = $request->input('days', []);
        Log::info("--- Starting updateWorkingHours for Doctor ID: {$doctorId} ---", ['Payload' => $submittedDaysData]);

        // أيام الأسبوع القياسية للمرور عليها وضمان معالجة كل يوم
        $daysOfWeek = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

        DB::beginTransaction();
        try {
            // المرور على *كل* أيام الأسبوع
            foreach ($daysOfWeek as $dayName) {
                Log::debug("Processing day: {$dayName}");

                // الحصول على بيانات اليوم المحدد من الطلب (إن وجدت)
                $data = $submittedDaysData[$dayName] ?? null;

                // تحديد إذا كان هذا اليوم مرسلاً كـ "نشط"
                // Checkbox غير المحدد لا يُرسل، لذا نتحقق من وجود المفتاح وقيمته (غالباً 'on')
                $isSubmittedAsActive = $data !== null && isset($data['active']) && $data['active'] === 'on';
                Log::debug("[{$dayName}] Submitted as Active: " . ($isSubmittedAsActive ? 'Yes' : 'No'));

                // البحث عن سجل يوم العمل الحالي في قاعدة البيانات (إن وجد)
                $existingWorkingDay = DoctorWorkingDay::where('doctor_id', $doctor->id)
                    ->where('day', $dayName)
                    ->first();

                if ($isSubmittedAsActive) {
                    // --- الحالة 1: اليوم محدد كـ نشط في الفورم ---

                    // التحقق من صحة الأوقات والمدة المرسلة
                    $startTimeStr = isset($data['start_time']) && preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $data['start_time']) ? $data['start_time'] . ':00' : null;
                    $endTimeStr = isset($data['end_time']) && preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $data['end_time']) ? $data['end_time'] . ':00' : null;
                    $duration = isset($data['appointment_duration']) && is_numeric($data['appointment_duration']) && $data['appointment_duration'] >= 5 ? (int) $data['appointment_duration'] : null;

                    Log::debug("[{$dayName}] Parsed Active Data: Start={$startTimeStr}, End={$endTimeStr}, Duration={$duration}");

                    // إذا كانت أي من البيانات المطلوبة لليوم النشط غير صالحة، أوقف العملية
                    if (!$startTimeStr || !$endTimeStr || !$duration || Carbon::parse($startTimeStr)->gte(Carbon::parse($endTimeStr))) {
                        Log::error("[{$dayName}] Invalid data for ACTIVE day. Rolling back transaction.");
                        session()->flash("error", "بيانات يوم '{$dayName}' غير صالحة (تأكد من الأوقات والمدة).");
                        // إيقاف العملية كلها لمنع الحفظ غير المتناسق
                        DB::rollback(); // التراجع الفوري
                        // إعادة التوجيه يجب أن تتم من الـ Controller، لكن يمكننا إرجاع false هنا للإشارة للفشل
                        return false; // أو رمي استثناء throw new \Exception(...)
                    }

                    // البيانات صالحة: قم بالتحديث أو الإنشاء
                    // (updateOrCreate آمنة هنا لأننا نمرر كل الحقول المطلوبة)
                    try {
                        $workingDay = DoctorWorkingDay::updateOrCreate(
                            ['doctor_id' => $doctor->id, 'day' => $dayName], // الشروط
                            [ // البيانات للتحديث/الإنشاء
                                'start_time' => $startTimeStr,
                                'end_time' => $endTimeStr,
                                'appointment_duration' => $duration,
                                'active' => true, // *** تأكيد أنه نشط ***
                            ]
                        );
                        Log::info("[{$dayName}] Successfully Saved (Active) working day ID: {$workingDay->id}");
                        // (اختياري) تحديث الاستراحات
                        // $this->handleBreaks($workingDay, $data['breaks'] ?? []);

                    } catch (\Exception $e) {
                        Log::error("!!! Exception during DB operation for Active Day {$dayName}: " . $e->getMessage());
                        throw $e; // رمي الاستثناء لإيقاف وح عمل Rollback
                    }
                } else {
                    // --- الحالة 2: اليوم *غير* محدد كـ نشط في الفورم ---
                    // نريد التأكد من أنه غير نشط في قاعدة البيانات

                    if ($existingWorkingDay) {
                        // إذا كان السجل موجوداً في قاعدة البيانات
                        if ($existingWorkingDay->active) {
                            // وكان نشطاً، قم بتعطيله
                            Log::info("[{$dayName}] Deactivating existing working day ID: {$existingWorkingDay->id}");
                            $existingWorkingDay->breaks()->delete(); // حذف الاستراحات المرتبطة
                            $existingWorkingDay->update(['active' => false]);
                        } else {
                            // إذا كان موجوداً ولكنه غير نشط أصلاً، لا تفعل شيئاً
                            Log::debug("[{$dayName}] Existing record is already inactive.");
                        }
                    } else {
                        // إذا لم يكن السجل موجوداً أصلاً، لا داعي لفعل شيء
                        Log::debug("[{$dayName}] No existing record found. Nothing to deactivate.");
                    }
                } // نهاية التحقق من isSubmittedAsActive

                Log::info("--- Finished processing day: {$dayName} ---");
            } // نهاية foreach

            Log::info("--- Committing Transaction for Doctor ID: {$doctorId} ---");
            DB::commit();
            // إزالة رسالة الخطأ القديمة إذا كانت العملية ناجحة
            session()->forget('error'); // مسح أي رسالة خطأ سابقة
            session()->flash('success', 'تم تحديث جدول ساعات العمل بنجاح.');
           return redirect()->route('admin.Doctors.index'); // إرجاع true للإشارة للنجاح

        } catch (\Exception $e) {
            Log::error("--- Rolling Back Transaction for Doctor ID: {$doctorId}. Error: " . $e->getMessage() . " at Line: " . $e->getLine() . " ---");
            DB::rollback();
            // استخدام رسالة الخطأ من الاستثناء إذا كانت موجودة، وإلا رسالة عامة
            $errorMessage = session()->has('error') ? session('error') : ('حدث خطأ غير متوقع أثناء تحديث جدول ساعات العمل: ' . $e->getMessage());
            session()->flash('error', $errorMessage);
            return false; // إرجاع false للإشارة للفشل
        }
    }

    public function editBasicInfo($id)
    {
        $sections = Section::all();
        // $appointments = Appointment::all(); // إزالة إذا لم تستخدم
        $doctor = Doctor::with(['section', 'image'])->findOrFail($id); // لا نحتاج workingDays هنا

        return view('Dashboard.Doctors.edit', compact( // يعرض ملف edit.blade.php الأصلي
            'sections',
            // 'appointments',
            'doctor'
            // لا نمرر بيانات ساعات العمل هنا
        ));
    }
    public function getScheduleForEdit($id)
    {
        $doctor = Doctor::with(['workingDays.breaks'])->findOrFail($id);

        $daysOfWeek = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        $workingHoursData = [];
        foreach ($daysOfWeek as $day) {
            $workingDay = $doctor->workingDays->firstWhere('day', $day); // استخدام firstWhere أبسط

            $workingHoursData[$day] = [
                'exists' => (bool)$workingDay,
                'active' => $workingDay?->active ?? false,
                'start_time' => $workingDay?->start_time ? Carbon::parse($workingDay->start_time)->format('H:i') : '',
                'end_time' => $workingDay?->end_time ? Carbon::parse($workingDay->end_time)->format('H:i') : '',
                'appointment_duration' => $workingDay?->appointment_duration ?? 30,
                'working_day_id' => $workingDay?->id ?? null,
                'breaks' => $workingDay?->breaks->map(function ($break) {
                    return [ /* ... تنسيق الاستراحات ... */];
                }) ?? collect()
            ];
        }

        return view('Dashboard.Doctors.edit_Days', compact( // *** يعرض ملف edit_Days.blade.php الجديد ***
            'doctor',
            'daysOfWeek',
            'workingHoursData'
        ));
    }
    protected function updateBreaks(DoctorWorkingDay $workingDay, array $breaksData)
    {
        // 1. حذف الاستراحات القديمة لهذا اليوم (أبسط طريقة للتعامل مع التغييرات)
        $workingDay->breaks()->delete();

        // 2. إضافة الاستراحات الجديدة
        foreach ($breaksData as $break) {
            if (!empty($break['start_time']) && !empty($break['end_time'])) {
                DoctorBreak::create([
                    'doctor_working_day_id' => $workingDay->id,
                    'start_time' => $break['start_time'],
                    'end_time' => $break['end_time'],
                    'reason' => $break['reason'] ?? null,
                ]);
            }
        }
    }
    public function update_password($request)
    {
        try {
            $doctor = Doctor::findorfail($request->id);
            $doctor->update([
                'password' => Hash::make($request->password)
            ]);

            session()->flash('update_password');
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function update_status($request)
    {
        try {
            $doctor = Doctor::findorfail($request->id);
            $doctor->update([
                'status' => $request->status
            ]);

            session()->flash('update_status');
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
