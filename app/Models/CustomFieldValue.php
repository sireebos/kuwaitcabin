<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CustomFieldValue extends Model
{
    protected $fillable = ['custom_field_id', 'fieldable_id', 'fieldable_type', 'value'];

    public function customField(): BelongsTo
    {
        return $this->belongsTo(CustomField::class);
    }

    public function fieldable(): MorphTo
    {
        return $this->morphTo();
    }
}
