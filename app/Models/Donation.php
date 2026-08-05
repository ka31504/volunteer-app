<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Helpers\MaskHelper;
use Illuminate\Support\Facades\Auth;

class Donation extends Model
{
    protected $fillable = [
        'project_id',
        'donor_name',
        'donor_phone',
        'sponsor_id',
        'type',
        'amount',
        'goods_description',
        'goods_quantity',
        'payment_method',
        'donated_at',
        'note',
    ];

    protected $casts = [
        'donated_at' => 'date',
        'amount'     => 'decimal:2',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function sponsor(): BelongsTo
    {
        return $this->belongsTo(Sponsor::class);
    }

    public function getFormattedAmountAttribute(): string
    {
        if ($this->type === 'goods') {
            return $this->goods_description . ' (x' . $this->goods_quantity . ')';
        }
        return number_format($this->amount, 0, ',', '.') . ' đ';
    }

    public function getTypeLabelAttribute(): string
    {
        return $this->type === 'money' ? 'Tiền mặt' : 'Hiện vật';
    }

    public function getPaymentLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'cash'     => 'Tiền mặt',
            'transfer' => 'Chuyển khoản',
            default    => 'Khác',
        };
    }
    public function getDisplayDonorNameAttribute(): ?string
    {
        if (Auth::check() && optional(Auth::user())->isAdmin()) {
            return $this->donor_name;
        }

        return MaskHelper::maskWords($this->donor_name);
    }

    public function getDisplayDonorPhoneAttribute(): ?string
    {
        if (Auth::check() && optional(Auth::user())->isAdmin()) {
            return $this->donor_phone;
        }

        return MaskHelper::maskWords($this->donor_phone);
    }
}
