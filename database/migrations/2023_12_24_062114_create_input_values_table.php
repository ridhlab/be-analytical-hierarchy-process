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
        Schema::create('input_values', function (Blueprint $table) {
            $table->id();
            $table->float('value')->nullable(false);
            $table->unsignedBigInteger('result_id')->nullable(false);
            $table->unsignedBigInteger('variable_input_id')->nullable(false);

            $table->foreign('result_id')->on('results')->references('id');
            $table->foreign('variable_input_id')->on('variable_inputs')->references('id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('input_values');
    }
};
