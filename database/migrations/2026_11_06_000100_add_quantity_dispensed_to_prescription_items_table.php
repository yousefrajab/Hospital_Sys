<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuantityDispensedToPrescriptionItemsTable extends Migration
{
    /**
     * How much of each item the pharmacy has already handed out, so a partially dispensed
     * prescription can't be dispensed in full a second time.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prescription_items', function (Blueprint $table) {
            $table->unsignedInteger('quantity_dispensed')->default(0)->after('quantity_prescribed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prescription_items', function (Blueprint $table) {
            $table->dropColumn('quantity_dispensed');
        });
    }
}
