<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDoctorIdToGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
 {
     Schema::table('groups', function (Blueprint $table) {
         $table->foreignId('doctor_id')->nullable()->constrained('doctors')->onDelete('set null');
         // $table->foreignId('section_id')->nullable()->constrained('sections')->onDelete('set null'); // إذا أضفت section_id
     });
 }

 public function down()
 {
     Schema::table('groups', function (Blueprint $table) {
         $table->dropForeign(['doctor_id']);
         $table->dropColumn('doctor_id');
         // $table->dropForeign(['section_id']);
         // $table->dropColumn('section_id');
     });
 }
}
