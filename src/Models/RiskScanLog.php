<?php

namespace Cloudspace\AML\Models;

use Illuminate\Database\Eloquent\Model;

class RiskScanLog extends Model
{
    protected $fillable = [
        'risk_scan_result_id',
        'risk_level',
        'match_count',
        'summary',
        'scanned_at',
    ];

    protected $casts = [
        'summary' => 'array',
        'scanned_at' => 'datetime',
    ];
}
