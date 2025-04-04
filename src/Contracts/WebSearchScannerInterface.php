<?php

namespace Cloudspace\AML\Contracts;

interface WebSearchScannerInterface
{
    public function scan(string $fullName): array;
}
