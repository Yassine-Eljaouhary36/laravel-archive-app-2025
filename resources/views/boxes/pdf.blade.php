<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>بطاقة العلبة - {{ $box->box_number }}</title>
    <style>
        @page {
            margin: 0;
            padding: 0;
            size: 150mm 100mm landscape;
        }
        body {
            font-family: 'xbriyaz', sans-serif;
            margin: 0;
            padding: 5mm;
            background-color: #ffffff;
            height: 90mm;
            width: 140mm;
        }
        .label-container {
            height: 100%;
            position: relative;
            border: 2px solid #2c5f91;
            border-radius: 8px;
            padding: 5mm;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            background: linear-gradient(to bottom, #ffffff 0%, #f8fafc 100%);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3mm;
            padding-bottom: 3mm;
            border-bottom: 2px solid #2c5f91;
        }
        .logo {
            height: 20mm;
            max-width: 40mm;
        }
        .title {
            font-size: 18pt;
            font-weight: bold;
            color: #2c5f91;
            text-align: center;
            flex-grow: 1;
        }
        .box-number {
            font-size: 24pt;
            font-weight: bold;
            color: #2c5f91;
            text-align: center;
            margin: 3mm 0;
            background-color: #f0f4f8;
            padding: 2mm;
            border-radius: 4px;
            border: 1px dashed #2c5f91;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 4mm;
            margin-top: 5mm;
            background-color: #f8fafc;
            border-radius: 4px;
            border: 1px solid #e2e8f0;
            padding: 5mm;
        }
        .info-item {
            display: flex;
            align-items: center;
        }
        .info-label {
            font-size: 15pt;
            font-weight: bold;
            color: #2c5f91;
            min-width: 40mm;
            padding: 2mm;
            /* background-color: #f0f4f8; */
            border-radius: 4px;
        }
        .info-value {
            font-size: 15pt;
            flex-grow: 1;
            padding: 2mm;
            /* background-color: #f8fafc; */
            border-radius: 4px;
            /* border: 1px solid #e2e8f0; */
            min-height: 8mm;
        }
        .barcode {
            margin-top: 3mm;
            text-align: center;
            font-family: 'libre-barcode';
            font-size: 32pt;
            letter-spacing: 2px;
            padding: 1mm 0;
            background-color: #f8fafc;
            border-radius: 4px;
            border: 1px solid #e2e8f0;
        }
        .footer {
            position: absolute;
            bottom: 5mm;
            left: 5mm;
            right: 5mm;
            text-align: center;
            font-size: 9pt;
            color: #718096;
            padding-top: 2mm;
            border-top: 1px solid #e2e8f0;
        }
        .qr-code {
            position: absolute;
            bottom: 15mm;
            right: 5mm;
            width: 25mm;
            height: 25mm;
            background-color: white;
            padding: 2mm;
            border: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="label-container">
        <div class="header">
            @if(file_exists(public_path('images/logo.svg')))
                <img src="{{ public_path('images/logo.svg') }}" class="logo">
            @endif
            <div class="title">بطاقة تعريف العلبة</div>
        </div>

        <div class="box-number">العلبة رقم: {{ $box->box_number }}</div>

        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">المحكمة:</span>
                <span class="info-value">{{ $box->tribunal->tribunal ?? 'غير محددة' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">رقم قاعدة الحفظ:</span>
                <span class="info-value">{{ $box->savingBase->number ?? 'غير محدد' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">المصلحة:</span>
                <span class="info-value">{{ $box->file_type }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">نوع الملفات:</span>
                <span class="info-value">{{ $box->type }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">سنة الحكم:</span>
                <span class="info-value">{{ $box->year_of_judgment ?? 'غير محددة'}}</span>
            </div>
            <div class="info-item">
                <span class="info-label">عدد الملفات:</span>
                <span class="info-value">{{ $box->total_files }}</span>
            </div>
        </div>

        {{-- <div class="barcode">*{{ $box->box_number }}*</div> --}}

        <div class="footer">
            نظام إدارة الأرشيف - {{ config('app.name') }}
        </div>
    </div>
</body>
</html>