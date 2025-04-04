<?php

namespace Cloudspace\AML\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RiskScanResult extends Model
{
    protected $fillable = [
        'full_name',
        'bvn',
        'nin',
        'risk_level',
        'scanned_at',
    ];

    protected $dates = ['scanned_at'];

    public function matches(): HasMany
    {
        return $this->hasMany(RiskMatch::class);
    }
}
