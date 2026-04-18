<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tailored Resume</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.2;
            color: #000;
            margin: -10px; /* Small negative margin to maximize space */
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .header h1 {
            font-size: 26pt;
            margin: 0 0 5px 0;
            font-weight: normal;
        }
        .contact-info {
            font-size: 10pt;
            margin-bottom: 5px;
        }
        .contact-info a {
            color: #000;
            text-decoration: none;
        }
        .section-title {
            font-size: 13pt;
            font-variant: small-caps;
            text-transform: uppercase;
            font-weight: bold;
            border-bottom: 1px solid #000;
            margin-top: 15px;
            margin-bottom: 5px;
            padding-bottom: 2px;
        }
        .item-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2px;
        }
        .item-table td {
            padding: 0;
            vertical-align: top;
        }
        .title {
            font-weight: bold;
            font-size: 12pt;
        }
        .date {
            text-align: right;
            font-size: 11pt;
            white-space: nowrap;
        }
        .subtitle {
            font-style: italic;
            font-size: 11pt;
        }
        ul {
            margin-top: 3px;
            margin-bottom: 10px;
            padding-left: 20px;
        }
        li {
            margin-bottom: 3px;
        }
        .skills-container {
            margin-top: 5px;
        }
        .summary-text {
            margin-top: 5px;
            margin-bottom: 10px;
            text-align: justify;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>{{ $resume['personal_details']['name'] ?? 'Professional Resume' }}</h1>
        <div class="contact-info">
            @if(isset($resume['personal_details']['phone']))
                {{ $resume['personal_details']['phone'] }} &nbsp;|&nbsp; 
            @endif
            @if(isset($resume['personal_details']['email']))
                <a href="mailto:{{ $resume['personal_details']['email'] }}">{{ $resume['personal_details']['email'] }}</a> &nbsp;|&nbsp; 
            @endif
            @if(isset($resume['personal_details']['linkedin']))
                <a href="{{ str_starts_with($resume['personal_details']['linkedin'], 'http') ? $resume['personal_details']['linkedin'] : 'https://'.$resume['personal_details']['linkedin'] }}">{{ str_replace(['https://', 'http://', 'www.'], '', $resume['personal_details']['linkedin']) }}</a> 
            @endif
            @if(isset($resume['personal_details']['github']))
                 &nbsp;|&nbsp; <a href="{{ str_starts_with($resume['personal_details']['github'], 'http') ? $resume['personal_details']['github'] : 'https://'.$resume['personal_details']['github'] }}">{{ str_replace(['https://', 'http://', 'www.'], '', $resume['personal_details']['github']) }}</a>
            @endif
        </div>
    </div>

    @if(!empty($resume['summary']))
    <div class="section-title">Professional Summary</div>
    <div class="summary-text">
        {{ $resume['summary'] }}
    </div>
    @endif

    @if(!empty($resume['experience']))
    <div class="section-title">Experience</div>
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
    <div class="section-title">Education</div>
    @foreach($resume['education'] as $edu)
    <div style="margin-bottom: 5px;">
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
    <div class="section-title">Technical Skills</div>
    <div class="skills-container">
        <strong>Languages & Technologies:</strong> {{ implode(', ', $resume['skills']) }}
    </div>
    @endif

</body>
</html>
