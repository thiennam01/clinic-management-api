<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\StatsService;
use Illuminate\Http\JsonResponse;

class StatsController extends Controller
{
    public function __construct(
        protected StatsService $statsService
    ) {}

    public function show(): JsonResponse
    {
        return response()->json([
            'message' => 'Statistics retrieved successfully.',
            'data' => $this->statsService->getOverview(),
        ]);
    }
}
