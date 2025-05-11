<?php

namespace App\Http\Controllers\Dashboard; // تأكد من المسار الصحيح

use App\Models\Bed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Bed\StoreBedRequest;
use App\Http\Requests\Dashboard\Bed\UpdateBedRequest;
use App\Models\Room; // لجلب الغرف لـ dropdown الفلتر
use App\Models\Section; // لجلب الأقسام لـ dropdown الفلتر

class BedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Bed::with(['room.section', 'currentAdmission.patient']) // جلب العلاقات
            ->orderBy('room_id')
            ->orderBy('bed_number');

        // الفلترة
        if ($request->filled('search_bed')) {
            $query->where('bed_number', 'like', '%' . $request->search_bed . '%');
        }
        if ($request->filled('room_id_filter')) {
            $query->where('room_id', $request->room_id_filter);
        }
        if ($request->filled('section_id_filter')) {
            // الفلترة بالقسم تتطلب join أو whereHas
            $query->whereHas('room', function ($q) use ($request) {
                $q->where('section_id', $request->section_id_filter);
            });
        }
        if ($request->filled('bed_type_filter')) {
            $query->where('type', $request->bed_type_filter);
        }
        if ($request->filled('bed_status_filter')) {
            $query->where('status', $request->bed_status_filter);
        }

        $beds = $query->paginate(15)->appends($request->query());

        // بيانات لـ dropdowns الفلترة
        $sections = Section::orderByTranslation('name')->get(); // افترض أن اسم القسم ليس مترجمًا حاليًا
        // يمكنك جلب الغرف بطريقة أكثر كفاءة إذا كان لديك عدد كبير جدًا
        $rooms = Room::orderBy('room_number')->get();

        // قيم Enum للأسرة (من الموديل)
        $bedTypes = Bed::getBedTypes(); // افترض أن لديك هذه الدالة في موديل Bed
        $bedStatuses = Bed::getAllBedStatuses(); // افترض أن لديك هذه الدالة في موديل Bed

        return view('Dashboard.Beds.index', compact('beds', 'sections', 'rooms', 'request', 'bedTypes', 'bedStatuses'));
    }

    public function create()
    {
        // جلب الغرف لعرضها في قائمة منسدلة، مع اسم القسم لتسهيل الاختيار
        $rooms = Room::with('section')->orderBy('section_id')->orderBy('room_number')->get()->map(function ($room) {
            // إنشاء نص وصفي للغرفة
            $room->display_name = $room->room_number . ($room->section ? ' (' . $room->section->name . ')' : '');
            return $room;
        });

        // قيم Enum للـ dropdowns (من الموديل)
        $bedTypes = Bed::getBedTypes();
        // عند إنشاء سرير جديد، عادة ما يكون متاحًا
        $initialBedStatuses = [
            Bed::STATUS_AVAILABLE => 'متاح',
            // يمكنك إضافة حالات أخرى إذا سمحت بإنشاء سرير وهو ليس متاحًا مباشرة
        ];

        Log::info("BedController@create: Loading create bed form.");
        return view('Dashboard.Beds.create', compact('rooms', 'bedTypes', 'initialBedStatuses'));
    }

    public function store(StoreBedRequest $request) // استخدام StoreBedRequest
    {
        try {
            $validatedData = $request->validated();

            // (اختياري) يمكنك معالجة bed_number هنا إذا أردت (مثل جعله بأحرف كبيرة)
            // $validatedData['bed_number'] = strtoupper($validatedData['bed_number']);

            $bed = Bed::create($validatedData);

            Log::info("BedController@store: New bed created successfully with ID: {$bed->id}", $validatedData);
            return redirect()->route('admin.beds.index') // تأكد من اسم الـ route
                ->with('success', 'تمت إضافة السرير بنجاح.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error("BedController@store: Validation exception during bed creation.", ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error("BedController@store: General exception during bed creation: " . $e->getMessage(), [
                'trace' => substr($e->getTraceAsString(), 0, 500),
                'request_data' => $request->except('password', '_token')
            ]);
            return redirect()->back()->withInput()->with('error', 'حدث خطأ غير متوقع أثناء إضافة السرير.');
        }
    }

    public function show(Bed $bed)
    {
        // تحميل العلاقات اللازمة لعرض التفاصيل
        $bed->load([
            'room' => function ($query) {
                $query->with('section'); // تحميل القسم الخاص بالغرفة
            },
            'currentAdmission' => function ($query) {
                $query->with('patient.image', 'doctor'); // تحميل المريض الحالي (مع صورته) والطبيب المسؤول عن الدخول
            },
            // 'admissionsHistory' => function ($query) { // (اختياري) لتحميل تاريخ سجلات الدخول على هذا السرير
            //     $query->with('patient')->orderBy('admission_date', 'desc');
            // }
        ]);

        // الحصول على القيم النصية للـ Enum
        $bedTypeDisplay = Bed::getBedTypes()[$bed->type] ?? $bed->type;
        $bedStatusDisplay = Bed::getAllBedStatuses()[$bed->status] ?? $bed->status;

        Log::info("BedController@show: Displaying details for Bed ID: {$bed->id}");

        return view('Dashboard.Beds.show', compact('bed', 'bedTypeDisplay', 'bedStatusDisplay'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bed  $bed
     * @return \Illuminate\Http\Response
     */
    public function edit(Bed $bed)
    {
        $bed->load('room.section'); // تحميل الغرفة والقسم لعرض معلوماتهما

        // جلب الغرف لعرضها في قائمة منسدلة، مع اسم القسم
        $rooms = Room::with('section')->orderBy('section_id')->orderBy('room_number')->get()->map(function ($room) {
            $room->display_name = $room->room_number . ($room->section ? ' (' . $room->section->name . ')' : '');
            return $room;
        });

        $bedTypes = Bed::getBedTypes();
        $bedStatuses = Bed::getAllBedStatuses(); // كل الحالات الممكنة للتعديل

        Log::info("BedController@edit: Loading edit form for Bed ID: {$bed->id}");
        return view('Dashboard.Beds.edit', compact('bed', 'rooms', 'bedTypes', 'bedStatuses'));
    }

    public function update(UpdateBedRequest $request, Bed $bed)
    {
        try {
            $validatedData = $request->validated();

            // (اختياري) معالجة البيانات قبل التحديث
            // $validatedData['bed_number'] = strtoupper($validatedData['bed_number']);

            $bed->update($validatedData);

            // لا تنسَ أن موديل Bed لديه booted method قد يقوم بتحديث حالة الغرفة تلقائيًا
            // إذا لم يكن كذلك، قد تحتاج لاستدعاء $bed->room->updateOccupancyStatus(); هنا إذا تغيرت حالة السرير

            Log::info("BedController@update: Bed ID {$bed->id} updated successfully.", $validatedData);
            return redirect()->route('admin.beds.index')
                             ->with('success', 'تم تعديل بيانات السرير بنجاح.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error("BedController@update: Validation exception for Bed ID {$bed->id}.", ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error("BedController@update: General exception for Bed ID {$bed->id}: " . $e->getMessage(), [
                'trace' => substr($e->getTraceAsString(), 0, 500)
            ]);
            return redirect()->back()->withInput()->with('error', 'حدث خطأ غير متوقع أثناء تعديل السرير.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bed  $bed (Route Model Binding)
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bed $bed)
    {
        try {
            // تحقق إذا كان السرير مشغولاً حاليًا
            if ($bed->status === Bed::STATUS_OCCUPIED || $bed->currentAdmission) {
                Log::warning("BedController@destroy: Attempt to delete occupied Bed ID {$bed->id}.");
                return redirect()->route('admin.beds.index')
                                 ->with('error', 'لا يمكن حذف السرير. إنه مشغول حاليًا بمريض.');
            }

            $bedNumber = $bed->bed_number;
            $roomNumber = $bed->room->room_number ?? 'غير معروفة'; // للحصول على رقم الغرفة قبل الحذف

            $bed->delete(); // الـ booted method في موديل Bed سيهتم بتحديث حالة الغرفة

            Log::info("BedController@destroy: Bed '{$bedNumber}' in Room '{$roomNumber}' (ID {$bed->id}) deleted successfully.");
            return redirect()->route('admin.beds.index')
                             ->with('success', "تم حذف السرير '{$bedNumber}' من الغرفة '{$roomNumber}' بنجاح.");
        } catch (\Exception $e) {
            Log::error("BedController@destroy: Error deleting Bed ID {$bed->id}: " . $e->getMessage(), ['trace' => substr($e->getTraceAsString(), 0, 500)]);
            return redirect()->route('admin.beds.index')
                             ->with('error', 'حدث خطأ أثناء حذف السرير: ' . $e->getMessage());
        }
    }
}
