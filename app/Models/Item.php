<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'effect',
    ];

    /**
     * Obtém os registros de inventário para este item.
     */
    public function inventories(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }
}
