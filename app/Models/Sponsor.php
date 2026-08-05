<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sponsor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'phone',
        'email',
        'address',
        'tax_code',
        'notes',
    ];

    // ─── Relationships ────────────────────────────────────────────
    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    // ─── Computed Attributes ──────────────────────────────────────
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'individual'   => 'Cá nhân',
            'organization' => 'Tổ chức',
            default        => $this->type,
        };
    }

    public function getTotalContributedAttribute(): float
    {
        return (float) $this->donations()->where('type', 'money')->sum('amount');
    }

    public function getDonationCountAttribute(): int
    {
        return $this->donations()->count();
    }

    // ─── Scopes ───────────────────────────────────────────────────
    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('phone', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%")
              ->orWhere('tax_code', 'like', "%{$term}%");
        });
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }
}