<?php

namespace App\Interfaces\Doctors;

use Illuminate\Http\Request; // *** إضافة: استيراد Request ***
use App\Http\Requests\StoreDoctorsRequest;
use App\Http\Requests\UpdateDoctorsRequest;

interface DoctorRepositoryInterface
{
    // get Doctor
    public function index();

    // create Doctor
    public function create();

    // store Doctor
    public function store(StoreDoctorsRequest $request);

    // update Doctor
    public function update(UpdateDoctorsRequest $request);

    // destroy Doctor
    public function destroy($request); // يمكن تحديد نوع request هنا إذا كان محدداً دائماً

    // edit Doctor
    public function edit($id);

    public function editBasicInfo($id); // *** جديد: لعرض فورم التعديل الأساسي ***
    public function getScheduleForEdit($id); // *** جديد: لجلب بيانات فورم تعديل الجدول ***
    // *** إضافة: دالة تحديث ساعات العمل ***
    public function updateWorkingHours(Request $request, $doctorId);

    // update_password
    public function update_password(Request $request); // تحديد نوع Request أفضل

    // update_status
    public function update_status(Request $request); // تحديد نوع Request أفضل

}
