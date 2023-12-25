<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InputValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'value', 'variable_input_id'
    ];

    public function result(): BelongsTo
    {
        return $this->belongsTo(Result::class, 'result_id');
    }
}
