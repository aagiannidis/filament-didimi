<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SecureDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'doc_attachable_id',
        'doc_attachable_type',
        'type',
        'original_filename',
        'random_filename',
        'flags',
        'uploaded_by_user_id',
        'uploaded_at',
        'status_history',
        'expiry_date',
    ];

    protected $casts = [
        'flags' => 'array',
        'status_history' => 'array',
        'uploaded_at' => 'datetime',
        'expiry_date' => 'datetime',
    ];

    public function uploadedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by_user_id');
    }

    public function docAttachable(): MorphTo
    {
        return $this->morphTo();
    }
}
