<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Trap extends Model
{
    use HasFactory;

    protected $fillable = [
        'phase_id',
        'name',
        'description',
        'damage',
        'trigger_condition',
    ];

    /**
     * Obtém a fase à qual esta armadilha pertence.
     */
    public function phase(): BelongsTo
    {
        return $this->belongsTo(Phase::class);
    }
}
