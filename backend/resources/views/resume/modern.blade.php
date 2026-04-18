<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Modern Resume</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.3;
            color: #333;
            margin: -10px;
        }
        .header {
            border-bottom: 3px solid #1a365d; /* Dark blue accent */
            padding-bottom: 15px;
            margin-bottom: 15px;
            text-align: right; /* Right align for modern feel */
        }
        .header h1 {
            font-size: 28pt;
            margin: 0 0 5px 0;
            color: #1a365d; /* Dark blue header */
            font-weight: 300;
        }
        .contact-info {
            font-size: 10pt;
            color: #666;
        }
        .contact-info a {
            color: #1a365d;
            text-decoration: none;
        }
        .section-title {
            font-size: 14pt;
            color: #1a365d;
            font-weight: 600;
            border-bottom: 1px solid #e2e8f0;
            margin-top: 20px;
            margin-bottom: 10px;
            padding-bottom: 4px;
        }
        .item-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }
        .item-table td {
            padding: 0;
            vertical-align: top;
        }
        .title {
            font-weight: 600;
            font-size: 12pt;
            color: #2d3748;
        }
        .date {
            text-align: right;
            font-size: 10.5pt;
            color: #718096;
            white-space: nowrap;
        }
        .subtitle {
            font-size: 11pt;
            color: #4a5568;
            font-weight: 500;
        }
        ul {
            margin-top: 5px;
            margin-bottom: 15px;
            padding-left: 18px;
            color: #4a5568;
        }
        li {
            margin-bottom: 4px;
        }
        .skills-container {
            margin-top: 10px;
            color: #4a5568;
        }
        .summary-text {
            margin-top: 10px;
            margin-bottom: 15px;
            color: #4a5568;
            line-height: 1.5;
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
                <a href="mailto:{{ $resume['personal_details']['email'] }}">{{ $resume['personal_details']['email'] }}</a>
            @endif
            <br>
            @if(isset($resume['personal_details']['linkedin']))
                <a href="{{ str_starts_with($resume['personal_details']['linkedin'], 'http') ? $resume['personal_details']['linkedin'] : 'https://'.$resume['personal_details']['linkedin'] }}">{{ str_replace(['https://', 'http://', 'www.'], '', $resume['personal_details']['linkedin']) }}</a> 
            @endif
            @if(isset($resume['personal_details']['github']))
                 &nbsp;|&nbsp; <a href="{{ str_starts_with($resume['personal_details']['github'], 'http') ? $resume['personal_details']['github'] : 'https://'.$resume['personal_details']['github'] }}">{{ str_replace(['https://', 'http://', 'www.'], '', $resume['personal_details']['github']) }}</a>
            @endif
        </div>
    </div>

    @if(!empty($resume['summary']))
    <div class="section-title">Profile</div>
    <div class="summary-text">
        {{ $resume['summary'] }}
    </div>
    @endif

    @if(!empty($resume['experience']))
    <div class="section-title">Professional Experience</div>
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
    <div style="margin-bottom: 10px;">
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
    <div class="section-title">Technical Expertise</div>
    <div class="skills-container">
        {{ implode(' • ', $resume['skills']) }}
    </div>
    @endif

</body>
</html>
