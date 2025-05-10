<?php

namespace App\Http\Controllers\Dashboard; // تأكد أن هذا هو المسار الصحيح لمجلد الـ Controllers

use App\Models\Room;
use App\Models\Section; // لاستخدامه في فورم الإنشاء/التعديل
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Room\StoreRoomRequest;   // استخدام الـ FormRequest
use App\Http\Requests\Dashboard\Room\UpdateRoomRequest; // استخدام الـ FormRequest
use Illuminate\Http\Request; // لاستخدامه في دالة index للفلترة

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Room::with(['section', 'beds']) // جلب العلاقات لتجنب N+1 queries
                     ->orderBy('section_id')
                     ->orderBy('room_number');

        // تطبيق الفلاتر
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

        // الترقيم مع الحفاظ على بارامترات الفلتر
        $rooms = $query->paginate(15)->appends($request->query());

        // جلب البيانات اللازمة لـ dropdowns الفلترة
        $sections = Section::orderByTranslation('name')->get(); // فرز حسب الاسم المترجم

        // قيم Enum للـ dropdowns (يفضل تعريفها كمصفوفات ثابتة في الموديل أو helper)
        $roomTypes = [
            'patient_room' => 'غرفة مريض', 'private_room' => 'غرفة خاصة',
            'semi_private_room' => 'غرفة شبه خاصة', 'icu_room' => 'غرفة عناية مركزة',
            'examination_room' => 'غرفة فحص', 'consultation_room' => 'غرفة استشارة',
            'treatment_room' => 'غرفة علاج', 'operating_room' => 'غرفة عمليات',
            'radiology_room' => 'غرفة أشعة', 'laboratory_room' => 'غرفة مختبر',
            'office' => 'مكتب', 'other' => 'أخرى'
        ];
        $roomStatuses = [
            'available' => 'متاحة', 'partially_occupied' => 'مشغولة جزئيًا',
            'fully_occupied' => 'مشغولة كليًا', 'out_of_service' => 'خارج الخدمة'
        ];

        return view('Dashboard.Rooms.index', compact('rooms', 'sections', 'request', 'roomTypes', 'roomStatuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function show(Room $room)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function edit(Room $room)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Room $room)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function destroy(Room $room)
    {
        //
    }
}
