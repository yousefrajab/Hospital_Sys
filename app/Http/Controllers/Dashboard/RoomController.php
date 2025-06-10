<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Room;
use App\Models\Section;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Room\StoreRoomRequest;
use App\Http\Requests\Dashboard\Room\UpdateRoomRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // مهم لتسجيل الأخطاء

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Room::with(['section', 'beds'])
            ->orderBy('section_id')
            ->orderBy('room_number');

        if ($request->filled('search_room')) {
            $query->where('room_number', 'like', '%' . $request->search_room . '%');
        }
        if ($request->filled('section_id_filter')) {
            $query->where('section_id', $request->section_id_filter);
        }
        if ($request->filled('room_type_filter')) {
            $query->where('type', $request->room_type_filter);
        }
        if ($request->filled('room_status_filter')) {
            $query->where('status', $request->room_status_filter);
        }

        $rooms = $query->paginate(15)->appends($request->query());
        $sections = Section::orderByTranslation('name')->get();
        // ** افترض أن name عمود عادي **

        // قيم Enum للـ dropdowns
        $roomTypes = [
            'patient_room' => 'غرفة مريض',
            'private_room' => 'غرفة خاصة',
            'semi_private_room' => 'غرفة شبه خاصة',
            'icu_room' => 'غرفة عناية مركزة',
            'examination_room' => 'غرفة فحص',
            'consultation_room' => 'غرفة استشارة',
            'treatment_room' => 'غرفة علاج',
            'operating_room' => 'غرفة عمليات',
            'radiology_room' => 'غرفة أشعة',
            'laboratory_room' => 'غرفة مختبر',
            'office' => 'مكتب',
            'other' => 'أخرى'
        ];
        $roomStatuses = [ // كل الحالات للفلترة
            'available' => 'متاحة',
            'partially_occupied' => 'مشغولة جزئيًا',
            'fully_occupied' => 'مشغولة كليًا',
            'out_of_service' => 'خارج الخدمة'
        ];

        return view('Dashboard.Rooms.index', compact('rooms', 'sections', 'request', 'roomTypes', 'roomStatuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sections = Section::orderByTranslation('name')->get(); // ** افترض أن name عمود عادي **

        $roomTypes = [
            'patient_room' => 'غرفة مريض',
            'private_room' => 'غرفة خاصة',
            'semi_private_room' => 'غرفة شبه خاصة',
            'icu_room' => 'غرفة عناية مركزة',
            'examination_room' => 'غرفة فحص',
            'consultation_room' => 'غرفة استشارة',
            'treatment_room' => 'غرفة علاج',
            'operating_room' => 'غرفة عمليات',
            'radiology_room' => 'غرفة أشعة',
            'laboratory_room' => 'غرفة مختبر',
            'office' => 'مكتب',
            'other' => 'أخرى'
        ];
        $genderTypes = [
            'any' => 'أي جنس',
            'male' => 'ذكور فقط',
            'female' => 'إناث فقط',
            'mixed' => 'مختلط (ذكور وإناث)'
        ];
        $initialRoomStatuses = [ // الحالات الممكنة عند الإنشاء
            'available' => 'متاحة',
            'out_of_service' => 'خارج الخدمة'
        ];

        Log::info("RoomController@create: Loading create room form.");
        return view('Dashboard.Rooms.create', compact('sections', 'roomTypes', 'genderTypes', 'initialRoomStatuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoomRequest $request) // استخدام StoreRoomRequest
    {
        try {
            $validatedData = $request->validated();
            Room::create($validatedData);

            Log::info("RoomController@store: New room created successfully.", $validatedData);
            return redirect()->route('admin.rooms.index')
                ->with('success', 'تمت إضافة الغرفة بنجاح.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error("RoomController@store: Validation exception.", ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error("RoomController@store: General exception: " . $e->getMessage(), ['trace' => substr($e->getTraceAsString(), 0, 500)]);
            return redirect()->back()->withInput()->with('error', 'حدث خطأ غير متوقع أثناء إضافة الغرفة.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        $room->load(['section', 'beds' => function ($query) {
            $query->with('currentAdmission.patient'); // جلب المريض الحالي لكل سرير إذا كان مشغولاً
        }]);

        $allRoomTypes = Room::getRoomTypes();
        $allGenderTypes = Room::getGenderTypes();
        $allRoomStatuses = Room::getAllStatuses();

        $roomTypeDisplay = $allRoomTypes[$room->type] ?? $room->type;
        $genderTypeDisplay = $allGenderTypes[$room->gender_type] ?? $room->gender_type;
        $statusDisplay = $allRoomStatuses[$room->status] ?? $room->status;

        return view('Dashboard.Rooms.show', compact('room', 'roomTypeDisplay', 'genderTypeDisplay', 'statusDisplay'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room $room) // استخدام Route Model Binding
    {
        $sections = Section::orderByTranslation('name')->get(); // ** افترض أن name عمود عادي **
        $roomTypes = [
            'patient_room' => 'غرفة مريض',
            'private_room' => 'غرفة خاصة',
            'semi_private_room' => 'غرفة شبه خاصة',
            'icu_room' => 'غرفة عناية مركزة',
            'examination_room' => 'غرفة فحص',
            'consultation_room' => 'غرفة استشارة',
            'treatment_room' => 'غرفة علاج',
            'operating_room' => 'غرفة عمليات',
            'radiology_room' => 'غرفة أشعة',
            'laboratory_room' => 'غرفة مختبر',
            'office' => 'مكتب',
            'other' => 'أخرى'
        ];
        $genderTypes = [
            'any' => 'أي جنس',
            'male' => 'ذكور فقط',
            'female' => 'إناث فقط',
            'mixed' => 'مختلط (ذكور وإناث)'
        ];
        $roomStatuses = [ // كل الحالات الممكنة للتعديل
            'available' => 'متاحة',
            'partially_occupied' => 'مشغولة جزئيًا',
            'fully_occupied' => 'مشغولة كليًا',
            'out_of_service' => 'خارج الخدمة'
        ];

        return view('Dashboard.Rooms.edit', compact('room', 'sections', 'roomTypes', 'genderTypes', 'roomStatuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoomRequest $request, Room $room) // استخدام UpdateRoomRequest و Route Model Binding
    {
        try {
            $validatedData = $request->validated();
            $room->update($validatedData);

            Log::info("RoomController@update: Room ID {$room->id} updated successfully.", $validatedData);
            return redirect()->route('admin.rooms.index')
                ->with('success', 'تم تعديل بيانات الغرفة بنجاح.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error("RoomController@update: Validation exception for Room ID {$room->id}.", ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error("RoomController@update: General exception for Room ID {$room->id}: " . $e->getMessage(), ['trace' => substr($e->getTraceAsString(), 0, 500)]);
            return redirect()->back()->withInput()->with('error', 'حدث خطأ غير متوقع أثناء تعديل الغرفة.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room) // استخدام Route Model Binding
    {
        try {
            // تحقق إذا كانت الغرفة تحتوي على أسرة مشغولة قبل الحذف
            if ($room->beds()->where('status', \App\Models\Bed::STATUS_OCCUPIED)->exists()) { // استخدام ثابت من موديل Bed
                Log::warning("RoomController@destroy: Attempt to delete room ID {$room->id} which has occupied beds.");
                return redirect()->route('admin.rooms.index')
                    ->with('error', 'لا يمكن حذف الغرفة. تحتوي على أسرة مشغولة حاليًا.');
            }

            $roomName = $room->room_number;
            $room->delete();

            Log::info("RoomController@destroy: Room '{$roomName}' (ID {$room->id}) deleted successfully.");
            return redirect()->route('admin.rooms.index')
                ->with('success', "تم حذف الغرفة '{$roomName}' بنجاح.");
        } catch (\Exception $e) {
            Log::error("RoomController@destroy: Error deleting room ID {$room->id}: " . $e->getMessage(), ['trace' => substr($e->getTraceAsString(), 0, 500)]);
            return redirect()->route('admin.rooms.index')
                ->with('error', 'حدث خطأ أثناء حذف الغرفة: ' . $e->getMessage());
        }
    }
}
