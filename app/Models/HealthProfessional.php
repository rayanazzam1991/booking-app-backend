<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HealthProfessional extends Model
{
    /** @use HasFactory<\Database\Factories\HealthProfessionalFactory> */
    use HasFactory;

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'health_professional_services')
            ->withPivot(['price', 'duration_minutes', 'notes','status'])
            ->withTimestamps();
    }
}
