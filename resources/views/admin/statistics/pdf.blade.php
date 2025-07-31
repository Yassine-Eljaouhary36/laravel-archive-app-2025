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
            background-color: #f8fafc;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 5px;
            border: 1px solid #e2e8f0;
        }
        
        .filter-item {
            margin-bottom: 10px;
        }
        
        .summary-cards {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            gap: 20px;
        }
        
        .card {
            flex: 1;
            background-color: #f8fafc;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            border: 1px solid #e2e8f0;
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
        
        tr:nth-child(even) {
            background-color: #f8fafc;
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
            max-height: 80px;
            max-width: 200px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        @if(file_exists(public_path('images/logo.svg')))
            <img src="{{ public_path('images/logo.svg') }}" class="logo"> 
        @endif
        <div class="title">{{ __('تقرير إحصائيات الأرشيف المُعالَج') }}</div>
        <div>{{ __('تاريخ التقرير: ') }} {{ now()->format('Y-m-d') }}</div>
    </div>

    <div class="filters">
        <div class="filter-item"><strong>{{ __('الفترة:') }}</strong> {{ $filters['date_from'] }} إلى {{ $filters['date_to'] ?: 'الكل' }}</div>
        <div class="filter-item"><strong>{{ __('المحكمة:') }}</strong> {{ $filters['tribunal'] }}</div>
        <div class="filter-item"><strong>{{ __('سنة الحكم:') }}</strong> {{ $filters['year'] }}</div>
    </div>

    <div class="summary-cards">
        <div class="card">
            <div class="card-title">{{ __('إجمالي العلب المعالجة') }}</div>
            <div class="card-value">{{ $totalStats['total_boxes'] }}</div>
        </div>
        <div class="card">
            <div class="card-title">{{ __('إجمالي الملفات المعالجة') }}</div>
            <div class="card-value">{{ $totalStats['total_files'] }}</div>
        </div>
        <div class="card">
            <div class="card-title">{{ __('متوسط الملفات لكل علبة') }}</div>
            <div class="card-value">
                @php
                    $average = $totalStats['total_boxes'] > 0 
                        ? round($totalStats['total_files'] / $totalStats['total_boxes'], 1)
                        : 0;
                @endphp
                {{ $average }}
            </div>
        </div>
    </div>

    <h3 class="section-title">{{ __('التوزيع حسب النوع') }}</h3>
    <table>
        <thead>
            <tr>
                <th>{{ __('النوع') }}</th>
                <th>{{ __('عدد العلب') }}</th>
                <th>{{ __('النسبة') }}</th>
                <th>{{ __('عدد الملفات') }}</th>
                <th>{{ __('النسبة') }}</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalBoxes = $totalStats['total_boxes'] > 0 ? $totalStats['total_boxes'] : 1;
                $totalFiles = $totalStats['total_files'] > 0 ? $totalStats['total_files'] : 1;
            @endphp
            
            @forelse($statsByType as $type => $stats)
                @php
                    $boxPercentage = round(($stats['total_boxes'] / $totalBoxes) * 100, 1);
                    $filePercentage = round(($stats['total_files'] / $totalFiles) * 100, 1);
                @endphp
                <tr>
                    <td>{{ $type }}</td>
                    <td>{{ $stats['total_boxes'] }}</td>
                    <td>
                        {{ $boxPercentage }}%
                        <div class="percentage-bar">
                            <div class="percentage-fill" style="width: {{ $boxPercentage }}%"></div>
                        </div>
                    </td>
                    <td>{{ $stats['total_files'] }}</td>
                    <td>
                        {{ $filePercentage }}%
                        <div class="percentage-bar">
                            <div class="percentage-fill" style="width: {{ $filePercentage }}%"></div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">{{ __('لا توجد بيانات') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        {{ __('تم الإنشاء في: ') }} {{ now()->format('Y-m-d H:i:s') }} | 
        {{ __('إجمالي الصفحات: ') }} {PAGENO}
    </div>
</body>
</html>