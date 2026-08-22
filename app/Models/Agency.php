<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agency extends Model
{
    /**
     * Agency represents an operational rescue organization.
     *
     * Contains relations to `resources`, `volunteers`, and `reports`.
     */
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'user_id', 'name', 'registration_number', 'type', 'description',
        'contact_email', 'contact_phone', 'address', 'region', 'state',
        'country', 'latitude', 'longitude', 'status', 'verified_by',
        'verified_at', 'specializations', 'total_teams', 'total_volunteers',
        'rescue_success_rate', 'is_deployed', 'logo', 'documents',
    ];

    protected function casts(): array
    {
        return [
            'specializations' => 'array',
            'documents' => 'array',
            'is_deployed' => 'boolean',
            'verified_at' => 'datetime',
            'rescue_success_rate' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function resources()
    {
        return $this->hasMany(Resource::class);
    }

    public function volunteers()
    {
        return $this->hasMany(Volunteer::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function sosRequests()
    {
        return $this->hasMany(SOSRequest::class, 'assigned_agency_id');
    }

    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function getTypeLabel(): string
    {
        return str_replace('_', ' ', ucfirst($this->type));
    }

    public function shortName(int $len = 20): string
    {
        if (empty($this->name)) {
            return '';
        }

        return (string) str($this->name)->limit($len)->toString();
    }
}
