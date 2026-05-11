<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SOSRequest extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'sos_requests';

    protected $fillable = [
        'user_id', 'disaster_id', 'victim_name', 'victim_phone',
        'victim_count', 'latitude', 'longitude', 'address', 'message',
        'severity', 'type', 'status', 'assigned_agency_id',
        'assigned_at', 'responded_at', 'resolved_at',
        'response_time_minutes', 'media',
    ];

    protected function casts(): array
    {
        return [
            'media' => 'array',
            'assigned_at' => 'datetime',
            'responded_at' => 'datetime',
            'resolved_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function disaster()
    {
        return $this->belongsTo(Disaster::class);
    }

    public function assignedAgency()
    {
        return $this->belongsTo(Agency::class, 'assigned_agency_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCritical($query)
    {
        return $query->whereIn('severity', ['critical', 'high']);
    }

    public function getSeverityColor(): string
    {
        return match ($this->severity) {
            'critical' => '#ff3b3b',
            'high' => '#ff8c00',
            'medium' => '#ffd700',
            'low' => '#4caf50',
            default => '#9e9e9e',
        };
    }
}
