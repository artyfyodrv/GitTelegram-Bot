<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Repository extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner',
        'name',
    ];

    public $timestamps = false;

    public function webhooks(): HasMany
    {
        return $this->hasMany(Webhook::class);
    }
}
