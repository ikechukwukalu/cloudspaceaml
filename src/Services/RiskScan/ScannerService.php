<?php

namespace Cloudspace\AML\Services\RiskScan;

abstract class ScannerService
{
    abstract public function scan(string $fullName, null|int $scanResultId = null): array;

    protected null|int $riskScanResultId = null;

    public function withScanResultId(int $id): static
    {
        $this->riskScanResultId = $id;
        return $this;
    }

}
