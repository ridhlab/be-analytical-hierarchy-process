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
            $table->after('compare2_variable_output_id', function (Blueprint $table) {
                $table->integer('value')->nullable(false);
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('matrix_compares', function (Blueprint $table) {
            $table->dropColumn('value');
        });
    }
};
