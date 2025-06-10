<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGlobalIdentifiersTable extends Migration
{
    public function up(): void
    {
        Schema::create('global_identifiers', function (Blueprint $table) {
            $table->id();
            $table->string('national_id')->unique(); // الإيميل الفريد
            $table->string('owner_type'); // النوع (مثل: Patient, Doctor, Employee)
            $table->unsignedBigInteger('owner_id'); // ID الخاص بالجدول
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('global_identifiers');
    }
};
