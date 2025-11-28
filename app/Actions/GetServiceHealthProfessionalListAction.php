<?php

namespace App\Actions;

use App\Models\Service;
use Illuminate\Database\Eloquent\Collection;

class GetServiceHealthProfessionalListAction
{
    public function handle(Service $service): Collection
    {
        return $service->professionals()->get();

    }
}
