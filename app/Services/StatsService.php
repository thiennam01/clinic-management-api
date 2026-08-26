<?php

namespace App\Services;

use App\Repositories\StatsRepository;

class StatsService
{
    public function __construct(
        protected StatsRepository $repository
    ) {}

    public function getOverview(): array
    {
        return $this->repository->getOverview();
    }
}
