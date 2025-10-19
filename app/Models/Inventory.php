<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Inventory extends Model
{
    use HasFactory;

    // Types of inventory transactions
    public const TYPE_IN = 'masuk';
    public const TYPE_OUT = 'keluar';

    // Status values
    public const STATUS_RECEIVED = 'received';
    public const STATUS_PROCESSED = 'processed';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'item_id',
        'user_id',
        'tipe',
        'jumlah',
        'status',
        'keterangan',
        'reference_id',
        'reference_type',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the item that owns the inventory record.
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Get the user who created the inventory record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include incoming items.
     */
    public function scopeIncoming(Builder $query): Builder
    {
        return $query->where('tipe', self::TYPE_IN);
    }

    /**
     * Scope a query to only include outgoing items.
     */
    public function scopeOutgoing(Builder $query): Builder
    {
        return $query->where('tipe', self::TYPE_OUT);
    }

    /**
     * Scope a query to only include items with a specific status.
     */
    public function scopeWithStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Get the reference model for the inventory record.
     */
    public function reference()
    {
        return $this->morphTo('reference');
    }
}
