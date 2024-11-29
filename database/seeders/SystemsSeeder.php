<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use DB;

class SystemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            DB::table('systems')->insert([
            'id' => Str::uuid()->toString(),
            'code' => 'MAX_COUNT_CHANGE_PAGE',
            'name' => 'Thiết lập tối đa dòng chuyển sang phân trang',
            'value' => 5000,           
            'active' => 1,
        ]);
    }
}
