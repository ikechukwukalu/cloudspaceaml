<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Risk Report - {{ $result->full_name }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        h2 { color: #b71c1c; }
    </style>
</head>
<body>
    <h2>AML Risk Report</h2>
    <p><strong>Full Name:</strong> {{ $result->full_name }}</p>
    <p><strong>BVN:</strong> {{ $result->bvn }}</p>
    <p><strong>NIN:</strong> {{ $result->nin }}</p>
    <p><strong>Risk Level:</strong> {{ strtoupper($result->risk_level) }}</p>
    <p><strong>Scanned At:</strong> {{ $result->scanned_at }}</p>

    @if($result->matches->count())
        <h4>Match Breakdown:</h4>
        <ul>
            @foreach($result->matches as $match)
                <li>
                    <strong>{{ $match->source }}</strong> ({{ $match->confidence }}%)<br>
                    Type: {{ $match->match_type }}<br>
                    {{ $match->description }}
                </li>
                <br>
            @endforeach
        </ul>
    @endif
</body>
</html>
{{--
    This template is used to generate a detailed risk report for a specific individual.
    It includes their personal information, risk level, and any matches found in the AML database.
    The report is styled for clarity and ease of reading.
    The report is generated in HTML format and can be sent via email or displayed on a web page.
    The report includes:
    - Full Name
    - BVN (Bank Verification Number)
    - NIN (National Identification Number)
    - Risk Level (High, Medium, Low)
    - Scanned At (timestamp of when the scan was performed)
    - Match Breakdown (list of matches found, including source, confidence level, type, and description)
    The report is designed to be clear and concise, providing all necessary information at a glance.
    The report is generated using Blade templating engine, which allows for easy integration with Laravel applications.
    The report is intended for use by compliance officers, risk managers, and other stakeholders involved in AML processes.
    The report is generated in HTML format, which can be easily converted to PDF or other formats if needed.
    The report is designed to be responsive and can be viewed on various devices, including desktops, tablets, and smartphones.
    The report is generated using data from the AML database, which is regularly updated to ensure accuracy and reliability.
    The report is intended to assist in the identification and management of potential AML risks, helping organizations to comply with regulatory requirements and protect against financial crime.
    The report is part of a larger AML compliance framework, which includes risk assessment, monitoring, and reporting processes.
--}}
