<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('discounts')->insert([
            [
                'percentage' => 15,
                'created_at' => Carbon::now()->toDateString(),
                'updated_at' => Carbon::now()->toDateString()
            ],
            [
                'percentage' => 30,
                'created_at' => Carbon::now()->toDateString(),
                'updated_at' => Carbon::now()->toDateString()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('discounts')->delete();
    }
};
