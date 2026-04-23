<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Creative Resume</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, sans-serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .header-bg {
            background-color: #2c3e50;
            color: #ffffff;
            padding: 25px;
            margin-bottom: 20px;
        }
        .header-bg h1 {
            margin: 0;
            font-size: 32pt;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .contact-table {
            width: 100%;
            margin-top: 10px;
            font-size: 10pt;
            color: #ecf0f1;
        }
        .contact-table td {
            padding: 0;
            vertical-align: top;
        }
        .contact-table a {
            color: #3498db;
            text-decoration: none;
        }
        .content-area {
            padding: 0 25px;
        }
        .section-title {
            font-size: 16pt;
            color: #2c3e50;
            text-transform: uppercase;
            border-bottom: 2px solid #3498db;
            margin-top: 20px;
            margin-bottom: 10px;
            padding-bottom: 2px;
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
            font-weight: bold;
            font-size: 12pt;
            color: #2c3e50;
        }
        .date {
            text-align: right;
            font-size: 10.5pt;
            color: #7f8c8d;
            white-space: nowrap;
        }
        .subtitle {
            font-size: 11pt;
            color: #34495e;
            font-style: italic;
        }
        ul {
            margin-top: 5px;
            margin-bottom: 15px;
            padding-left: 18px;
        }
        li {
            margin-bottom: 4px;
        }
        .skills-container {
            margin-top: 10px;
        }
        .skill-chip {
            background-color: #ecf0f1;
            padding: 3px 8px;
            border-radius: 4px;
            display: inline-block;
            margin-right: 5px;
            margin-bottom: 5px;
            font-size: 10pt;
            border: 1px solid #bdc3c7;
        }
    </style>
</head>
<body>

    <div class="header-bg">
        <h1>{{ $custom_name ?? $resume['personal_details']['name'] ?? 'Professional Resume' }}</h1>
        <table class="contact-table">
            <tr>
                <td style="width: 50%;">
                    @if(isset($resume['personal_details']['email']))
                        E: <a href="mailto:{{ $resume['personal_details']['email'] }}">{{ $resume['personal_details']['email'] }}</a><br>
                    @endif
                    @if(isset($resume['personal_details']['phone']))
                        P: {{ $resume['personal_details']['phone'] }}
                    @endif
                </td>
                <td style="width: 50%; text-align: right;">
                    @if(isset($resume['personal_details']['linkedin']))
                        L: <a href="{{ str_starts_with($resume['personal_details']['linkedin'], 'http') ? $resume['personal_details']['linkedin'] : 'https://'.$resume['personal_details']['linkedin'] }}">{{ str_replace(['https://', 'http://', 'www.'], '', $resume['personal_details']['linkedin']) }}</a><br>
                    @endif
                    @if(isset($resume['personal_details']['github']))
                        G: <a href="{{ str_starts_with($resume['personal_details']['github'], 'http') ? $resume['personal_details']['github'] : 'https://'.$resume['personal_details']['github'] }}">{{ str_replace(['https://', 'http://', 'www.'], '', $resume['personal_details']['github']) }}</a>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="content-area">
        @if(!empty($resume['summary']))
        <div class="section-title">Profile</div>
        <div style="text-align: justify; margin-bottom: 15px;">
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
        <div style="margin-bottom: 15px;">
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
        <div class="section-title">Expertise</div>
        <div class="skills-container">
            @foreach($resume['skills'] as $skill)
                <span class="skill-chip">{{ $skill }}</span>
            @endforeach
        </div>
        @endif
    </div>

</body>
</html>
