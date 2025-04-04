<?php

namespace Cloudspace\AML\Services\RiskScan;

use Cloudspace\AML\Models\RiskScanResult;
use Illuminate\Support\Carbon;

class RiskScanViewerService
{
    public function list(array $filters = [])
    {
        $query = RiskScanResult::with('matches')->latest();

        if (!empty($filters['risk_level'])) {
            $query->where('risk_level', $filters['risk_level']);
        }

        if (!empty($filters['name'])) {
            $query->where('full_name', 'like', '%' . $filters['name'] . '%');
        }

        if (!empty($filters['from'])) {
            $query->whereDate('created_at', '>=', Carbon::parse($filters['from']));
        }

        if (!empty($filters['to'])) {
            $query->whereDate('created_at', '<=', Carbon::parse($filters['to']));
        }

        return $query->paginate(15);
    }

    /**
     * Get a risk scan result by ID.
     *
     * @param int|string $id
     * @return RiskScanResult|null
     */
    public function getById(int|string $id): null|RiskScanResult
    {
        return RiskScanResult::with('matches')->find($id);
    }
}
