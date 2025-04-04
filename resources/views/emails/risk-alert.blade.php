<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>High-Risk Alert: {{ $result->full_name }}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; background-color: #f9f9f9; padding: 20px;">

    <h2 style="color: #d32f2f;">High-Risk Alert</h2>

    <p><strong>Full Name:</strong> {{ $result->full_name }}</p>
    <p><strong>BVN:</strong> {{ $result->bvn ?? 'N/A' }}</p>
    <p><strong>NIN:</strong> {{ $result->nin ?? 'N/A' }}</p>
    <p><strong>Risk Level:</strong> <span style="color: #d32f2f;">{{ strtoupper($result->risk_level) }}</span></p>
    <p><strong>Scanned At:</strong> {{ $result->scanned_at->format('Y-m-d H:i') }}</p>

    @if($result->matches->count())
        <h3>Matches Found ({{ $result->matches->count() }})</h3>
        <ul>
            @foreach($result->matches as $match)
                <li>
                    <strong>Source:</strong> {{ $match->source }}<br>
                    <strong>Type:</strong> {{ $match->match_type }}<br>
                    <strong>Confidence:</strong> {{ $match->confidence }}%<br>
                    <strong>Description:</strong> {{ $match->description }}
                </li>
                <br>
            @endforeach
        </ul>
    @else
        <p>No specific match details available.</p>
    @endif

    <p style="margin-top: 30px;">📍 This is an automated alert from the AML Risk Scanner.</p>

</body>
</html>
