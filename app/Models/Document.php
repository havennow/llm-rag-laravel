<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Document extends Model
{

    protected $fillable = [
        'title',
        'source',
        'type',
        'metadata',
    ];

    protected function casts(): array
    {

        return [
            'metadata' => 'array',
        ];

    }

    public function chunks(): HasMany
    {
        return $this->hasMany(DocumentChunk::class);
    }

}
