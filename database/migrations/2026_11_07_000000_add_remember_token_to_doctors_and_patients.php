<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * "Remember me" on the sign-in page stores a token on the user row. The other five user tables
 * have the column; doctors and patients did not, so remembering a doctor or patient crashed.
 */
return new class extends Migration
{
    public function up(): void
    {
        foreach (['doctors', 'patients'] as $table) {
            if (! Schema::hasColumn($table, 'remember_token')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->rememberToken();
                });
            }
        }
    }

    public function down(): void
    {
        foreach (['doctors', 'patients'] as $table) {
            if (Schema::hasColumn($table, 'remember_token')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->dropRememberToken();
                });
            }
        }
    }
};
