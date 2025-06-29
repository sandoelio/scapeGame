<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Npc extends Model
{
    use HasFactory;

    protected $fillable = [
        'phase_id',
        'name',
        'description',
        'dialog',
        'is_friendly',
    ];

    /**
     * Obtém a fase à qual este NPC pertence.
     */
    public function phase(): BelongsTo
    {
        return $this->belongsTo(Phase::class);
    }
}
