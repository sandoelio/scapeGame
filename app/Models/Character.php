<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Character extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'health_points',
        'max_health_points',
        'level',
        'experience',
    ];

    /**
     * Obtém o usuário ao qual este personagem pertence.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtém os itens no inventário do personagem.
     */
    public function inventory(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }

    /**
     * Obtém o progresso do personagem nas fases.
     */
    public function progress(): HasMany
    {
        return $this->hasMany(PlayerProgress::class);
    }

    /**
     * Obtém o histórico de ações do personagem.
     */
    public function actionHistory(): HasMany
    {
        return $this->hasMany(ActionHistory::class);
    }
}
