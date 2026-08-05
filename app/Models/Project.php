<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status',
        'target_amount',
        'current_amount',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date'    => 'date',
        'end_date'      => 'date',
        'target_amount' => 'decimal:2',
        'current_amount'=> 'decimal:2',
    ];

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class);
    }

    /**
     * Tính phần trăm tiến độ
     */
    public function progressPercentage(): float
{
    if (!$this->target_amount || $this->target_amount <= 0) {
        return 0;
    }

    return max(0, round(($this->current_amount / $this->target_amount) * 100, 2));
}

}