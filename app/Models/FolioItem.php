<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FolioItem extends Model
{
    protected $fillable = ['folio_id', 'description', 'type', 'amount', 'notes'];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function folio(): BelongsTo
    {
        return $this->belongsTo(Folio::class);
    }
}

