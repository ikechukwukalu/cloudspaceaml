<?php

namespace Cloudspace\AML\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiskMatch extends Model
{
    protected $fillable = [
        'risk_scan_result_id',
        'source',
        'match_type',
        'description',
        'confidence',
    ];

    public function result(): BelongsTo
    {
        return $this->belongsTo(RiskScanResult::class, 'risk_scan_result_id');
    }
}
