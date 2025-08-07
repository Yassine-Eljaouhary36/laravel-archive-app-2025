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
            background: linear-gradient(to bottom, #ffffff 0%, #ffffff 100%);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3mm;
            padding-bottom: 3mm;
            border-bottom: 2px solid #2c5f91;
            text-align: center;
        }
        .logo {
            height: 20mm;
            max-width: 350mm;
            display: inline-block;
        }
        .title {
            font-size: 17pt;
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
            border-radius: 14px;
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
            font-size: 13pt;
            font-weight: bold;
            color: #2c5f91;
            min-width: 40mm;
            padding: 2mm;
            /* background-color: #f0f4f8; */
            border-radius: 4px;
        }
        .info-value {
            font-size: 13pt;
            flex-grow: 1;
            padding: 2mm;
            /* background-color: #f8fafc; */
            border-radius: 4px;
            /* border: 1px solid #e2e8f0; */
            min-height: 8mm;
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
            @if(file_exists(public_path('images/logo.png')))
                <img src="{{ public_path('images/logo.png') }}" class="logo">
            @endif
            <div class="title">بطاقة تعريف العلبة</div>
        </div>

        <div class="box-number">العلبة رقم: {{ $box->box_number }}</div>

        <div class="info-grid">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <!-- Info items -->
                    <td style="vertical-align: top;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td><strong class="info-label">المحكمة:</strong></td>
                                <td class="info-value">{{ $box->tribunal->tribunal ?? 'غير محددة' }}</td>
                            </tr>
                            <tr>
                                <td><strong class="info-label">نوع الملفات:</strong></td>
                                <td class="info-value">{{ $box->type }}</td>
                            </tr>
                            <tr>
                                <td><strong class="info-label">رقم قاعدة الحفظ:</strong></td>
                                <td class="info-value" style="padding-right: 25px;">{{ $box->savingBase->number ?? 'غير محدد' }}</td>
                            </tr>
                            <tr>
                                <td><strong class="info-label">المصلحة:</strong></td>
                                <td class="info-value">{{ $box->file_type }}</td>
                            </tr>
                            <tr>
                                <td><strong class="info-label">سنة الحكم:</strong></td>
                                <td class="info-value">{{ $box->year_of_judgment ?? 'غير محددة'}}</td>
                            </tr>
                            <tr>
                                <td><strong class="info-label">عدد الملفات:</strong></td>
                                <td class="info-value">{{ $box->total_files }}</td>
                            </tr>
                        </table>
                    </td>
                    <!-- QR code -->
                    <td style="width: 120px; text-align: center; vertical-align: bottom;">
                        <img src="data:image/svg+xml;base64,{{ $qrCode }}" 
                            alt="QR Code" 
                            style="width: 100px; height: 100px;">
                    </td>
                </tr>
            </table>
        </div>

        <div class="footer">
            نظام إدارة الأرشيف - {{ config('app.name') }}
        </div>
    </div>
</body>
</html>