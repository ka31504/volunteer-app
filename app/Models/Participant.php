<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'full_name',
        'phone',
        'email',
        'birth_date',
        'gender',
        'address',
        'joined_at',
        'ended_at',
        'hours_contributed',
        'role',
        'status',
        'notes',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'joined_at'  => 'date',
        'ended_at'   => 'date',
    ];

    // ─── Relationships ────────────────────────────────────────────
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    // ─── Computed Attributes ──────────────────────────────────────
    public function getGenderLabelAttribute(): string
    {
        return match($this->gender) {
            'male'   => 'Nam',
            'female' => 'Nữ',
            default  => 'Khác',
        };
    }

    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            'volunteer'   => 'Tình nguyện viên',
            'team_lead'   => 'Trưởng nhóm',
            'coordinator' => 'Điều phối viên',
            default       => $this->role,
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'active'   => 'Đang hoạt động',
            'inactive' => 'Ngưng hoạt động',
            'pending'  => 'Chờ xác nhận',
            default    => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'active'   => 'success',
            'inactive' => 'danger',
            'pending'  => 'warning',
            default    => 'secondary',
        };
    }

    public function getAgeAttribute(): ?int
    {
        return $this->birth_date ? $this->birth_date->age : null;
    }

    public function getDurationDaysAttribute(): int
    {
        $end = $this->ended_at ?? now();
        return $this->joined_at->diffInDays($end);
    }

    // ─── Scopes ───────────────────────────────────────────────────
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeForProject($query, int $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('full_name', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%")
              ->orWhere('phone', 'like', "%{$term}%");
        });
    }
}