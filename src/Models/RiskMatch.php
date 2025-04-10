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
        'source_url',
        'match_hash',
        'response_payload'
    ];

    public function result(): BelongsTo
    {
        return $this->belongsTo(RiskScanResult::class, 'risk_scan_result_id');
    }
}
