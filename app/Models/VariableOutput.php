<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VariableOutput extends Model
{
    use HasFactory;

    protected $table = 'variable_outputs';

    public function compare1MatrixCompares(): HasMany
    {
        return $this->hasMany(MatrixCompare::class, 'compare1_variable_output_id');
    }

    public function compare2MatrixCompares(): HasMany
    {
        return $this->hasMany(MatrixCompare::class, 'compare2_variable_output_id');
    }

    public static function getNameById($id)
    {
        return VariableOutput::findOrFail($id)->name;
    }
}
