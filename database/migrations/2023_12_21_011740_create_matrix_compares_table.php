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
        Schema::create('matrix_compares', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('variable_input_id');
            $table->unsignedBigInteger('compare_1_output_id');
            $table->unsignedBigInteger('compare_2_output_id');

            $table->foreign('variable_input_id')->on('variable_inputs')->references('id');
            $table->foreign('compare_1_output_id')->on('variable_outputs')->references('id');
            $table->foreign('compare_2_output_id')->on('variable_outputs')->references('id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matrix_compares');
    }
};
