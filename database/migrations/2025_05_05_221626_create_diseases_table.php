<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiseasesTable extends Migration
{
    public function up()
    {
        Schema::create('diseases', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // اسم المرض (يجب أن يكون فريداً)
            $table->text('description')->nullable(); // وصف إضافي للمرض (اختياري)
            $table->boolean('is_chronic')->default(true); // علامة لتحديد إذا كان مزمناً (افتراضي نعم)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('diseases');
    }
}
