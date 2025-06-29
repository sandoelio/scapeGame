<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventory';

    protected $fillable = [
        'character_id',
        'item_id',
        'quantity',
    ];

    /**
     * Obtém o personagem ao qual este item de inventário pertence.
     */
    public function character(): BelongsTo
    {
        return $this->belongsTo(Character::class);
    }

    /**
     * Obtém o item deste registro de inventário.
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
