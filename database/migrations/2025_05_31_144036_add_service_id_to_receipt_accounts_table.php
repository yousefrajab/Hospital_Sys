<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddServiceIdToReceiptAccountsTable extends Migration
{
    public function up()
    {
        Schema::table('receipt_accounts', function (Blueprint $table) {
            // افترض أن عمود description موجود أو أضفه إذا لم يكن كذلك
            $table->foreignId('service_id')->nullable()->after('patient_id')->constrained('services')->onDelete('set null');
            // onDelete('set null') يعني إذا حُذفت الخدمة، يصبح service_id هنا NULL
        });
    }

    public function down()
    {
        Schema::table('receipt_accounts', function (Blueprint $table) {
            $table->dropForeign(['service_id']);
            $table->dropColumn('service_id');
        });
    }
}
