<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentChunk extends Model
{
    protected $fillable = [
        'document_id',
        'chunk_index',
        'content',
        'embedding',
        'metadata',
        'token_count',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public static function searchSimilar(
        array $embedding,
        int $limit = 5,
    ) {
        $vector = '[' . implode(',', $embedding) . ']';
        return static::query()
                     ->select('document_chunks.*')
                     ->selectRaw(
                         'embedding <=> ? AS distance',
                         [$vector]
                     )
                     ->orderByRaw(
                         'embedding <=> ?',
                         [$vector]
                     )
                     ->limit($limit)
                     ->get();

    }
}
