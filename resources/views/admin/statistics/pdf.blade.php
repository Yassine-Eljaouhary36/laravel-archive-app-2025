<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ __('تقرير إحصائيات الأرشيف المُعالَج') }}</title>
    <style>
        @font-face {
            font-family: 'XB Riyaz';
            src: url({{ storage_path('fonts/XBRiyaz.ttf') }}) format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        
        body {
            font-family: 'XB Riyaz', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 25px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #2c5f91;
        }
        
        .title {
            font-size: 28px;
            font-weight: bold;
            color: #2c5f91;
            margin-bottom: 10px;
        }
        
        .filters {
            background-color: #c1dcfc;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #5a5a5a;
        }
        
        .filter-item {
            margin-bottom: 10px;
        }
        
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .summary-cell {
            width: 50%;
            text-align: center;
            background-color: #c1dcfc;
            border: 1px solid #5a5a5a;
            border-radius: 8px;
            padding: 20px;
        }

        .card-title {
            font-weight: bold;
            margin-bottom: 10px;
            color: #4a5568;
        }

        .card-value {
            font-size: 32px;
            font-weight: bold;
            color: #2c5f91;
            margin: 10px 0;
        }
        
        .section-title {
            font-size: 22px;
            color: #2c5f91;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #e2e8f0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0 40px;
            border-radius: 8px;
            overflow: hidden;
        }
        
        th {
            background-color: #2c5f91;
            color: white;
            padding: 12px;
            text-align: center;
        }
        
        td {
            padding: 10px 12px;
            text-align: center;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .custom-style {
            background-color: #d5d5d5;
        }
        
        .percentage-bar {
            height: 20px;
            background-color: #e2e8f0;
            border-radius: 10px;
            margin-top: 5px;
            overflow: hidden;
        }
        
        .percentage-fill {
            height: 100%;
            background-color: #2c5f91;
            border-radius: 10px;
        }
        
        .footer {
            margin-top: 40px;
            text-align: left;
            font-size: 12px;
            color: #718096;
            padding-top: 15px;
            border-top: 1px solid #e2e8f0;
        }
        .logo {
            max-height: 200px;
            width: 700px;
            margin-bottom: 25px;
            margin-top: -50px
        }
    </style>
</head>
<body>
    <div class="header">
        @if(file_exists(public_path('images/logo.svg')))
            <img src="{{ public_path('images/logo.png') }}" class="logo"> 
        @endif
        <div class="title">{{ __('إحصائيات حول الأرشيف المُعالَج') }}</div>
        <div>{{ __('تاريخ التقرير: ') }} {{ now()->format('Y-m-d') }}</div>
    </div>

    <div class="filters">
        <div class="filter-item"><strong>{{ __('الفترة:') }}</strong> {{ $filters['date_from'] }} @isset($filters['date_from'])
            إلى
        @endisset  {{ $filters['date_to'] ?: 'الكل' }}</div>
        <div class="filter-item"><strong>{{ __('المحكمة:') }}</strong> {{ $filters['tribunal'] }}</div>
        <div class="filter-item"><strong>{{ __('سنة الحكم:') }}</strong> {{ $filters['year'] }}</div>
    </div>

    <table class="summary-table">
        <tr>
            <td class="summary-cell">
                <div class="card-title">{{ __('عدد العلب المعالجة') }}</div>
                <div class="card-value">{{ $totalStats['total_boxes'] }}</div>
            </td>
            <td class="summary-cell">
                <div class="card-title">{{ __('عدد الملفات المعالجة') }}</div>
                <div class="card-value">{{ $totalStats['total_files'] }}</div>
            </td>
        </tr>
    </table>


    <h3 class="section-title">{{ __('التوزيع حسب النوع') }}</h3>
    <table>
        <thead>
            <tr>
                <th>{{ __('النوع / السنة') }}</th>
                <th>{{ __('عدد الملفات') }}</th>
                <th>{{ __('النسبة') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($statsByType as $type => $typeData)
                <tr class="custom-style">
                    <td>{{ $type }}</td>
                    <td>{{ $typeData['total_files'] }}</td>
                    <td>
                        {{ round(($typeData['total_files'] / $totalStats['total_files']) * 100, 1) }}%
                    </td>
                </tr>
                @foreach($typeData['by_year'] as $year => $yearData)
                    <tr>
                        <td>{{ $year }}</td>
                        <td>{{ $yearData['files'] }}</td>
                        <td>
                            {{ round(($yearData['files'] / $totalStats['total_files']) * 100, 1) }}%
                        </td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">{{ __('لا توجد بيانات') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h3 class="section-title">{{ __('أكبر و أصغر سنة حكم حسب النوع') }}</h3>
        <table>
        <thead>
            <tr>
                <th>النوع</th>
                <th>أصغر سنة حكم</th>
                <th>أكبر سنة حكم</th>
            </tr>
        </thead>
        <tbody>
            @forelse($statsByType as $type => $typeData)
                @if ($typeData['min_year'] && $typeData['max_year'])
                    <tr>
                        <td>{{ $type }}</td>
                        <td>{{ $typeData['min_year'] }}</td>
                        <td>{{ $typeData['max_year'] }}</td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">{{ __('لا توجد بيانات') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>