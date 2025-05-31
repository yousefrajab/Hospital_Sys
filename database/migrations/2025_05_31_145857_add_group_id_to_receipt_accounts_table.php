<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGroupIdToReceiptAccountsTable extends Migration
{
    public function up()
    {
        Schema::table('receipt_accounts', function (Blueprint $table) {
            $table->foreignId('group_id')->nullable()->after('service_id')->constrained('groups')->onDelete('set null');
            // يمكن أن يكون السند مرتبطاً إما بخدمة أو بباقة، لذا service_id و group_id كلاهما nullable
            // يمكنك إضافة check constraint إذا كانت قاعدة بياناتك تدعمها لضمان أن واحداً منهما فقط هو الذي يتم تعيينه
        });
    }

    public function down()
    {
        Schema::table('receipt_accounts', function (Blueprint $table) {
            $table->dropForeign(['group_id']);
            $table->dropColumn('group_id');
        });
    }
}
