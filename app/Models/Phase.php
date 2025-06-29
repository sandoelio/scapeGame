<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Phase extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'order',
        'is_active',
    ];

    /**
     * Obtém os desafios desta fase.
     */
    public function challenges(): HasMany
    {
        return $this->hasMany(Challenge::class);
    }

    /**
     * Obtém as armadilhas desta fase.
     */
    public function traps(): HasMany
    {
        return $this->hasMany(Trap::class);
    }

    /**
     * Obtém os NPCs desta fase.
     */
    public function npcs(): HasMany
    {
        return $this->hasMany(Npc::class);
    }

    /**
     * Obtém o progresso dos jogadores nesta fase.
     */
    public function playerProgress(): HasMany
    {
        return $this->hasMany(PlayerProgress::class);
    }

    /**
     * Obtém o histórico de ações nesta fase.
     */
    public function actionHistory(): HasMany
    {
        return $this->hasMany(ActionHistory::class);
    }
}
