<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Webhook extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'hook_id',
        'repository_id'
    ];

    public $timestamps = false;

    public function repository(): BelongsTo
    {
        return $this->belongsTo(Repository::class, 'repository_id', 'id');
    }
}
