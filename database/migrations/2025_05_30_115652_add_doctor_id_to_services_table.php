<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDoctorIdToServicesTable extends Migration
{
    public function up()
    {
        Schema::table('services', function (Blueprint $table) { // 'services' or 'Services' based on your table name
            $table->foreignId('doctor_id')->nullable()->after('status')->constrained('doctors')->onDelete('set null');
            // onDelete('set null') means if a doctor is deleted, the service's doctor_id becomes null.
            // You could also use onDelete('cascade') if deleting a doctor should delete their services.
        });
    }

    public function down()
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign(['doctor_id']);
            $table->dropColumn('doctor_id');
        });
    }
}
