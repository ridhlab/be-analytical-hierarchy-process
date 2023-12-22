<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatrixCompare extends Model
{
    use HasFactory;

    public function variableInput(): BelongsTo
    {
        return $this->belongsTo(VariableInput::class . 'variable_input_id');
    }

    public function compare1VariableOutput(): BelongsTo
    {
        return $this->belongsTo(VariableOutput::class . 'compare1_variable_output_id');
    }


    public function compare2VariableOutput(): BelongsTo
    {
        return $this->belongsTo(VariableOutput::class . 'compare2_variable_output_id');
    }
}
