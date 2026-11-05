<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserTypeToNotificationsTable extends Migration
{
    /**
     * Notifications were stored by user_id only, so doctor #1 and patient #1 shared them.
     * user_type holds the model class of the recipient (App\Models\Doctor, App\Models\Patient, ...).
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->string('user_type')->nullable()->after('user_id');
            $table->index(['user_id', 'user_type', 'reader_status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'user_type', 'reader_status']);
            $table->dropColumn('user_type');
        });
    }
}
