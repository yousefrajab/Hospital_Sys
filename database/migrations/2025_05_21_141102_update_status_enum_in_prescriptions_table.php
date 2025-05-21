<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // لا تنسَ هذا

class UpdateStatusEnumInPrescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // قائمة بكل القيم الممكنة للحالة، بما في ذلك الجديدة
        $statusValues = [
            'new',
            'pending_review',
            'approved',
            'processing',         // <-- قيمة جديدة
            'ready_for_pickup',   // <-- قيمة جديدة
            'dispensed',
            'partially_dispensed',
            'on_hold',
            'cancelled_by_doctor',
            'cancelled_by_pharmacist',
            'cancelled_by_patient',
            'expired',
            'refill_requested'    // <-- قيمة جديدة
        ];
        // تحويل المصفوفة إلى سلسلة نصية مناسبة لـ ENUM
        $statusString = "'" . implode("','", $statusValues) . "'";

        // استخدام DB::statement لتعديل عمود ENUM
        // تأكد من أن القيمة الافتراضية (DEFAULT) لا تزال مناسبة أو قم بتحديثها إذا لزم الأمر
        DB::statement("ALTER TABLE prescriptions MODIFY COLUMN status ENUM({$statusString}) NOT NULL DEFAULT 'new'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // في دالة down، يجب أن تعيد تعريف الـ ENUM إلى حالته الأصلية (القائمة القديمة)
        // هذا مهم إذا احتجت للتراجع عن الـ migration
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
        $originalStatusString = "'" . implode("','", $originalStatusValues) . "'";
        DB::statement("ALTER TABLE prescriptions MODIFY COLUMN status ENUM({$originalStatusString}) NOT NULL DEFAULT 'new'");
    }
}
