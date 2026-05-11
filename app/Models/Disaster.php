<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Disaster extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'title', 'description', 'type', 'severity', 'status',
        'epicenter_lat', 'epicenter_lng', 'radius_km', 'affected_zones',
        'estimated_affected', 'confirmed_casualties', 'rescued_count',
        'created_by', 'started_at', 'contained_at',
    ];

    protected function casts(): array
    {
        return [
            'affected_zones' => 'array',
            'started_at' => 'datetime',
            'contained_at' => 'datetime',
        ];
    }

    public function sosRequests()
    {
        return $this->hasMany(SOSRequest::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
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

    public function getTypeIcon(): string
    {
        return match ($this->type) {
            'flood' => '🌊',
            'earthquake' => '🔴',
            'cyclone' => '🌀',
            'fire' => '🔥',
            'landslide' => '⛰️',
            'tsunami' => '🌊',
            'drought' => '☀️',
            'industrial' => '🏭',
            default => '⚠️',
        };
    }
}
