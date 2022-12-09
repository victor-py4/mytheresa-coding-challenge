<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('products')->insert([
            [
                'sku' => '000001',
                'name' => 'BV Lean leather ankle boots',
                'discount_id' => NULL,
                'category_id' => 1,
                'price' => 89000,
                'created_at' => Carbon::now()->toDateString(),
                'updated_at' => Carbon::now()->toDateString()
            ],
            [
                'sku' => '000002',
                'name' => 'BV Lean leather ankle boots',
                'discount_id' => NULL,
                'category_id' => 1,
                'price' => 99000,
                'created_at' => Carbon::now()->toDateString(),
                'updated_at' => Carbon::now()->toDateString()
            ],
            [
                'sku' => '000003',
                'name' => 'Ashlington leather ankle boots',
                'discount_id' => 1,
                'category_id' => 1,
                'price' => 71000,
                'created_at' => Carbon::now()->toDateString(),
                'updated_at' => Carbon::now()->toDateString()
            ],
            [
                'sku' => '000004',
                'name' => 'Naima embellished suede sandals',
                'discount_id' => NULL,
                'category_id' => 2,
                'price' => 79500,
                'created_at' => Carbon::now()->toDateString(),
                'updated_at' => Carbon::now()->toDateString()
            ],
            [
                'sku' => '000005',
                'name' => 'Nathane leather sneakers',
                'discount_id' => NULL,
                'category_id' => 3,
                'price' => 59000,
                'created_at' => Carbon::now()->toDateString(),
                'updated_at' => Carbon::now()->toDateString()
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('products')->delete();
    }
};
