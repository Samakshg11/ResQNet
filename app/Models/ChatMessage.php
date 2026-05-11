<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'sender_id', 'channel_id', 'channel_type', 'message',
        'attachments', 'read_by', 'is_alert', 'priority',
    ];

    protected function casts(): array
    {
        return [
            'attachments' => 'array',
            'read_by' => 'array',
            'is_alert' => 'boolean',
        ];
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
