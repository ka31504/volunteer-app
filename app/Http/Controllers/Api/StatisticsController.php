<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\StatisticsController as WebStatisticsController;
use Illuminate\Http\Request;

class StatisticsController extends WebStatisticsController
{
    public function index(Request $request)
    {
        $year = (int) $request->get('year', now()->year);
        $data = $this->getStatisticsData($year);

        return response()->json([
            'year' => $year,
            'available_years' => $this->getAvailableYears(),
            'data' => $data,
        ]);
    }
}