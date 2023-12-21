<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VariableInput extends Model
{
    use HasFactory;

    protected $table = 'variable_inputs';

    public function matrixCompares(): HasMany
    {
        return $this->hasMany(MatrixCompare::class, 'variable_input_id');
    }
}
