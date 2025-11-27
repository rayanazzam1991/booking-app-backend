<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthProfessionalService extends Model
{
    /** @use HasFactory<\Database\Factories\HealthProfessionalServiceFactory> */
    use HasFactory;

    protected $table = 'health_professional_services';

    protected $guarded = [];
}
