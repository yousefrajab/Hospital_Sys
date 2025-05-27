<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // *** لا تنسَ هذا السطر ***

class UpdateStatusEnumForPrescriptionsTable extends Migration // تأكد من أن اسم الكلاس يطابق اسم الملف
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // -- قائمة بكل القيم التي تحتاجها الآن وفي المستقبل القريب --
        // أضفت القيم التي ناقشناها سابقاً
        $allStatusValues = [
            'new',                      // موجودة
            'pending_review',           // موجودة
            'approved',                 // موجودة - الصيدلي سيراها بعد موافقة الطبيب على التجديد
            'processing',             // << جديدة: الصيدلي بدأ التجهيز
            'ready_for_pickup',       // << جديدة: جاهزة للاستلام من المريض
            'dispensed',                // موجودة
            'partially_dispensed',      // موجودة
            'on_hold',                  // موجودة
            'cancelled_by_doctor',      // موجودة
            'cancelled_by_pharmacist',  // موجودة
            'cancelled_by_patient',     // موجودة
            'expired',                  // موجودة
            'refill_requested',         // << جديدة: طلبها المريض، تنتظر مراجعة الطبيب
            // 'renewal_approved',      // << (اختياري) إذا أردت تمييز موافقة التجديد عن الموافقة العادية
            'refill_denied_by_doctor'   // << جديدة: الطبيب رفض طلب التجديد
            // يمكنك إضافة 'refill_denied_by_pharmacy' إذا احتجت لها
        ];

        // فرز القيم وإزالة المكررة (احتياطي)
        $uniqueStatusValues = array_unique($allStatusValues);
        sort($uniqueStatusValues); // الترتيب ليس ضرورياً لـ ENUM ولكنه جيد للتنظيم

        $statusEnumString = "'" . implode("','", $uniqueStatusValues) . "'";

        // استخدام DB::statement لتعديل عمود ENUM.
        // NOT NULL DEFAULT 'new' تبقى كما هي من تعريفك الأصلي.
        DB::statement("ALTER TABLE prescriptions MODIFY COLUMN status ENUM({$statusEnumString}) NOT NULL DEFAULT 'new'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // في دالة down، يجب أن تعيد تعريف الـ ENUM إلى حالته الأصلية (القائمة من ملفك الأصلي).
        // هذا مهم جداً إذا احتجت للتراجع عن الـ migration.
        $originalStatusValues = [
            'new',
            'pending_review',
            'approved',
            'dispensed',
            'partially_dispensed',
            'on_hold',
            'cancelled_by_doctor',
            'cancelled_by_pharmacist',
            'cancelled_by_patient',
            'expired'
        ];
        $originalStatusEnumString = "'" . implode("','", $originalStatusValues) . "'";
        DB::statement("ALTER TABLE prescriptions MODIFY COLUMN status ENUM({$originalStatusEnumString}) NOT NULL DEFAULT 'new'");
    }
}
