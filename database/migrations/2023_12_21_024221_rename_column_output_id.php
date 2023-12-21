<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('matrix_compares', function (Blueprint $table) {
            $table->dropForeign(['compare_1_variable_output_id']);
            $table->dropForeign(['compare_2_variable_output_id']);
            $table->renameColumn('compare_1_variable_output_id', 'compare1_variable_output_id');
            $table->renameColumn('compare_2_variable_output_id', 'compare2_variable_output_id');

            $table->foreign('compare1_variable_output_id')->on('variable_outputs')->references('id');
            $table->foreign('compare2_variable_output_id')->on('variable_outputs')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('matrix_compares', function (Blueprint $table) {
            $table->renameColumn('compare1_variable_output_id', 'compare_1_variable_output_id');
            $table->renameColumn('compare2_variable_output_id', 'compare_2_variable_output_id');
            $table->foreign('compare_1_variable_output_id')->on('variable_outputs')->references('id');
            $table->foreign('compare_2_variable_output_id')->on('variable_outputs')->references('id');
        });
    }
};
