<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Challenge extends Model
{
    use HasFactory;

    protected $fillable = [
        'phase_id',
        'name',
        'description',
        'type',
        'difficulty',
        'points',
        'order',
    ];

    /**
     * Obtém a fase à qual este desafio pertence.
     */
    public function phase(): BelongsTo
    {
        return $this->belongsTo(Phase::class);
    }

    /**
     * Obtém os códigos associados a este desafio.
     */
    public function codes(): HasMany
    {
        return $this->hasMany(Code::class);
    }

    /**
     * Obtém o progresso dos jogadores neste desafio.
     */
    public function playerProgress(): HasMany
    {
        return $this->hasMany(PlayerProgress::class);
    }
}
