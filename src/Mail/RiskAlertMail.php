<?php

namespace Cloudspace\AML\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Cloudspace\AML\Models\RiskScanResult;
use Illuminate\Queue\SerializesModels;

class RiskAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public RiskScanResult $result) {}

    public function build(): self
    {
        return $this->subject("HIGH-RISK DETECTED: {$this->result->full_name}")
            ->view('aml::emails.risk-alert')
            ->with(['result' => $this->result]);
    }
}
