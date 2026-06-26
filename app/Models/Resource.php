<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory, HasUuids;

    public const CATEGORIES = [
        'food' => 'Food',
        'medical_kit' => 'Medical kit',
        'vehicle' => 'Vehicle',
        'boat' => 'Boat',
        'rescue_team' => 'Rescue team',
        'fuel' => 'Fuel',
        'shelter_kit' => 'Shelter kit',
        'communication' => 'Communication',
        'heavy_equipment' => 'Heavy equipment',
        'other' => 'Other',
    ];

    public const STATUSES = [
        'available' => 'Available',
        'depleted' => 'Depleted',
    ];

    protected $fillable = [
        'agency_id', 'name', 'category', 'total_quantity',
        'available_quantity', 'deployed_quantity', 'unit',
        'minimum_threshold', 'lat', 'lng', 'status', 'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function isLow(): bool
    {
        return $this->available_quantity <= $this->minimum_threshold;
    }

    public function categoryLabel(): string
    {
        return self::CATEGORIES[$this->category] ?? str($this->category)->headline()->toString();
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? str($this->status)->headline()->toString();
    }

    public function deployedPercentage(): int
    {
        if ($this->total_quantity <= 0) {
            return 0;
        }

        return (int) round(($this->deployed_quantity / $this->total_quantity) * 100);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeOfCategory($query, ?string $category)
    {
        return $query->when($category, fn ($query) => $query->where('category', $category));
    }

    public function scopeWithStatus($query, ?string $status)
    {
        return $query->when($status, fn ($query) => $query->where('status', $status));
    }

    public function scopeShortage($query)
    {
        return $query->whereColumn('available_quantity', '<=', 'minimum_threshold');
    }
}
