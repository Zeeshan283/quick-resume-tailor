<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Compact Resume</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 9.5pt;
            line-height: 1.1;
            color: #000;
            margin: -25px -15px; /* Extremely tight margins to fit max content */
        }
        .header {
            text-align: center;
            margin-bottom: 5px;
        }
        .header h1 {
            font-size: 18pt;
            margin: 0;
            font-weight: bold;
        }
        .contact-info {
            font-size: 9pt;
            margin-top: 3px;
        }
        .contact-info a {
            color: #000;
            text-decoration: none;
        }
        .section-title {
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 1px solid #000;
            margin-top: 8px;
            margin-bottom: 4px;
        }
        .item-table {
            width: 100%;
            border-collapse: collapse;
        }
        .item-table td {
            padding: 0;
            vertical-align: top;
        }
        .title {
            font-weight: bold;
        }
        .date {
            text-align: right;
            white-space: nowrap;
        }
        .subtitle {
            font-style: italic;
        }
        ul {
            margin-top: 2px;
            margin-bottom: 6px;
            padding-left: 15px;
        }
        li {
            margin-bottom: 1px;
        }
        .skills-container {
            margin-top: 2px;
        }
        .summary-text {
            margin-top: 2px;
            margin-bottom: 4px;
            text-align: justify;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>{{ $resume['personal_details']['name'] ?? 'Professional Resume' }}</h1>
        <div class="contact-info">
            {{ implode('  |  ', array_filter([
                $resume['personal_details']['phone'] ?? null,
                $resume['personal_details']['email'] ?? null,
                isset($resume['personal_details']['linkedin']) ? str_replace(['https://', 'http://', 'www.'], '', $resume['personal_details']['linkedin']) : null
            ])) }}
        </div>
    </div>

    @if(!empty($resume['summary']))
    <div class="section-title">SUMMARY</div>
    <div class="summary-text">
        {{ $resume['summary'] }}
    </div>
    @endif

    @if(!empty($resume['experience']))
    <div class="section-title">EXPERIENCE</div>
    @foreach($resume['experience'] as $exp)
    <div>
        <table class="item-table">
            <tr>
                <td class="title">{{ $exp['role'] ?? 'Unknown Role' }}</td>
                <td class="date">{{ $exp['start_date'] ?? '' }} {!! isset($exp['end_date']) ? '&ndash; '.$exp['end_date'] : '' !!}</td>
            </tr>
            <tr>
                <td class="subtitle" colspan="2">{{ $exp['company'] ?? 'Unknown Company' }}</td>
            </tr>
        </table>
        <ul>
            @if(isset($exp['bullets']) && is_array($exp['bullets']))
                @foreach($exp['bullets'] as $bullet)
                    <li>{{ $bullet }}</li>
                @endforeach
            @endif
        </ul>
    </div>
    @endforeach
    @endif

    @if(!empty($resume['education']))
    <div class="section-title">EDUCATION</div>
    @foreach($resume['education'] as $edu)
    <div style="margin-bottom: 4px;">
        <table class="item-table">
            <tr>
                <td class="title">{{ $edu['institution'] ?? 'Unknown Institution' }}</td>
                <td class="date">{{ $edu['start_date'] ?? '' }} {!! isset($edu['end_date']) ? '&ndash; '.$edu['end_date'] : '' !!}</td>
            </tr>
            <tr>
                <td class="subtitle" colspan="2">{{ $edu['degree'] ?? 'Degree not specified' }}</td>
            </tr>
        </table>
    </div>
    @endforeach
    @endif

    @if(!empty($resume['skills']))
    <div class="section-title">SKILLS</div>
    <div class="skills-container">
        <strong>Technical Skills:</strong> {{ implode(', ', $resume['skills']) }}
    </div>
    @endif

</body>
</html>
