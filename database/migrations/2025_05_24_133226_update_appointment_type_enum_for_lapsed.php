 <?php

  use Illuminate\Database\Migrations\Migration;
  use Illuminate\Database\Schema\Blueprint;
  use Illuminate\Support\Facades\Schema;
  use Illuminate\Support\Facades\DB;

  class UpdateAppointmentTypeEnumForLapsed extends Migration
  {
      public function up()
      {
          // القائمة الجديدة مع القيم الجديدة
          $newTypes = [
              'غير مؤكد',
              'مؤكد',
              'منتهي',
              'ملغي',
              'فات الموعد' //  <--- الحالة الجديدة
              // يمكنك إضافة 'لم يحضر' (No Show) أيضاً إذا أردت
              // 'لم يحضر'
          ];
          $typeString = "'" . implode("','", $newTypes) . "'";
          DB::statement("ALTER TABLE appointments MODIFY COLUMN type ENUM({$typeString}) NOT NULL DEFAULT 'غير مؤكد'");
      }

      public function down()
      {
          // القائمة الأصلية بدون 'فات الموعد'
          $originalTypes = [
              'غير مؤكد',
              'مؤكد',
              'منتهي',
              'ملغي'
          ];
          $originalTypeString = "'" . implode("','", $originalTypes) . "'";
          DB::statement("ALTER TABLE appointments MODIFY COLUMN type ENUM({$originalTypeString}) NOT NULL DEFAULT 'غير مؤكد'");
      }
  }
