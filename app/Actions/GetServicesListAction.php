<?php

namespace App\Actions;

use App\Models\Service;
use Illuminate\Database\Eloquent\Collection;

class GetServicesListAction
{
    public function handle(): Collection
    {
        return Service::query()->get();

    }
}
