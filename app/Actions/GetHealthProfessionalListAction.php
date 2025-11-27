<?php

namespace App\Actions;

use App\Models\HealthProfessional;
use Illuminate\Database\Eloquent\Collection;

class GetHealthProfessionalListAction
{
    public function handle(): Collection
    {
        return HealthProfessional::query()->get();

    }
}
