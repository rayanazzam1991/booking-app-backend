<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    /** @use HasFactory<\Database\Factories\ServicesFactory> */
    use HasFactory;

    protected $guarded = [];

    public function professionals()
    {
        return $this->belongsToMany(HealthProfessional::class, 'health_professional_services')
            ->withPivot(['price', 'duration_minutes', 'notes','status'])
            ->withTimestamps();
    }
}
