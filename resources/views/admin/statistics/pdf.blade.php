<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ __('تقرير الإحصائيات') }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
        }
        .filters {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .filter-item {
            margin-bottom: 5px;
        }
        .summary-cards {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .card {
            width: 48%;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            text-align: center;
        }
        .card-title {
            font-weight: bold;
            margin-bottom: 10px;
        }
        .card-value {
            font-size: 24px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: left;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">{{ __('تقرير إحصائيات التصديق') }}</div>
        <div>{{ __('تاريخ التقرير: ') }} {{ now()->format('Y-m-d') }}</div>
    </div>

    <div class="filters">
        <div class="filter-item"><strong>{{ __('الفترة:') }}</strong> {{ $filters['date_from'] }} - {{ $filters['date_to'] ?: 'الكل' }}</div>
        <div class="filter-item"><strong>{{ __('المحكمة:') }}</strong> {{ $filters['tribunal'] }}</div>
        <div class="filter-item"><strong>{{ __('سنة الحكم:') }}</strong> {{ $filters['year'] }}</div>
    </div>

    <div class="summary-cards">
        <div class="card">
            <div class="card-title">{{ __('إجمالي العلب المصدقة') }}</div>
            <div class="card-value">{{ $totalStats['total_boxes'] }}</div>
        </div>
        <div class="card">
            <div class="card-title">{{ __('إجمالي الملفات المصدقة') }}</div>
            <div class="card-value">{{ $totalStats['total_files'] }}</div>
        </div>
    </div>

    <h3>{{ __('الإحصائيات حسب النوع') }}</h3>
    <table>
        <thead>
            <tr>
                <th>{{ __('النوع') }}</th>
                <th>{{ __('عدد العلب') }}</th>
                <th>{{ __('عدد الملفات') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($statsByType as $type => $stats)
                <tr>
                    <td>{{ $type }}</td>
                    <td>{{ $stats['total_boxes'] }}</td>
                    <td>{{ $stats['total_files'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">{{ __('لا توجد بيانات') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        {{ __('تم الإنشاء في: ') }} {{ now()->format('Y-m-d H:i:s') }}
    </div>
</body>
</html>