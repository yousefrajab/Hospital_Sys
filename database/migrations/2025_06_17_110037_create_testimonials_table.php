<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->nullable();
            $table->string('patient_name'); // اسم المريض
            $table->text('comment'); // نص التعليق
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // حالة التعليق
            $table->timestamp('approved_at')->nullable(); // تاريخ الموافقة
            $table->timestamps(); // created_at and updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('testimonials');
    }
};
