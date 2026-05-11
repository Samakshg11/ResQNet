<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'created_by', 'title', 'message', 'type', 'scope',
        'target_agencies', 'target_regions', 'delivery_channels',
        'recipients_count', 'acknowledged_count', 'disaster_id',
    ];

    protected function casts(): array
    {
        return [
            'target_agencies' => 'array',
            'target_regions' => 'array',
            'delivery_channels' => 'array',
        ];
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function disaster()
    {
        return $this->belongsTo(Disaster::class);
    }
}
