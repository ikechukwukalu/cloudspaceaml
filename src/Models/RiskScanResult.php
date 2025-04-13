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
        'other_identifiable_code',
        'other_identifiable_type',
        'email',
        'phone',
        'gender',
        'date_of_birth',
        'address',
        'website',
    ];

    protected $dates = ['scanned_at', 'date_of_birth'];

    public function matches(): HasMany
    {
        return $this->hasMany(RiskMatch::class);
    }
}
