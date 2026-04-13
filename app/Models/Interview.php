<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Interview extends Model
{
    protected $fillable = [
        'application_id',
        'scheduled_at',
        'status',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }
}
