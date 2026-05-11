<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Volunteer extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id', 'agency_id', 'national_id', 'skills',
        'certifications', 'languages', 'bio', 'availability',
        'current_lat', 'current_lng', 'current_task_id',
        'total_missions', 'rating', 'emergency_contact',
    ];

    protected function casts(): array
    {
        return [
            'skills' => 'array',
            'certifications' => 'array',
            'languages' => 'array',
            'emergency_contact' => 'array',
            'rating' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('availability', 'available');
    }
}
