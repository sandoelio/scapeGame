<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActionHistory extends Model
{
    use HasFactory;

    protected $table = 'action_history';

    protected $fillable = [
        'character_id',
        'phase_id',
        'action_type',
        'description',
        'result',
    ];

    /**
     * Obtém o personagem ao qual este histórico de ação pertence.
     */
    public function character(): BelongsTo
    {
        return $this->belongsTo(Character::class);
    }

    /**
     * Obtém a fase à qual este histórico de ação pertence.
     */
    public function phase(): BelongsTo
    {
        return $this->belongsTo(Phase::class);
    }
}
