<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'agency_id', 'disaster_id', 'sos_request_id', 'title',
        'description', 'category', 'ai_priority', 'ai_confidence',
        'ai_tags', 'media', 'latitude', 'longitude', 'status',
        'reviewed_by', 'review_notes', 'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'ai_tags' => 'array',
            'media' => 'array',
            'reviewed_at' => 'datetime',
            'ai_confidence' => 'decimal:4',
        ];
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function disaster()
    {
        return $this->belongsTo(Disaster::class);
    }

    public function sosRequest()
    {
        return $this->belongsTo(SOSRequest::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
