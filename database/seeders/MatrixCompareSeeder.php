<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MatrixCompareSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('matrix_compares')->insert([
            [
                'variable_input_id' => 2,
                'compare1_variable_output_id' => 1,
                'compare2_variable_output_id' => 1,
                'value' => 1,
            ],
            [
                'variable_input_id' => 2,
                'compare1_variable_output_id' => 1,
                'compare2_variable_output_id' => 1,
                'value' => 1,
            ]
        ]);
    }
}
