<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>قارئ رمز الاستجابة السريعة QR</title>
    <link href="{{ asset('css/qr-scanner.css') }}" rel="stylesheet">

</head>
<body>
    <div class="container">
        <header>
            <img src="{{ asset('images/logo.svg') }}" alt="Logo" style="height: 80px">
            <h4>قارئ رمز الاستجابة السريعة QR</h4>
        </header>
        
        <div class="scanner-container">
            <div id="reader" style="width: 100%;">
                <div class="scanner-overlay">
                    <div class="corner corner-tl"></div>
                    <div class="corner corner-tr"></div>
                    <div class="corner corner-bl"></div>
                    <div class="corner corner-br"></div>
                    <div class="scan-line"></div>
                </div>
            </div>
            
            <div class="controls">
                <button id="start">بدء المسح</button>
                <button id="stop">إيقاف المسح</button>
            </div>
            
            <p class="status" id="status">جاهز لمسح رموز QR</p>
        </div>
        
        <div id="show" style="display: none;">
            <h4>نتيجة المسح</h4>
            <p id="result"></p>
            
            <div class="action-buttons">
                <button id="copy">نسخ النص</button>
                <button id="open">فتح الرابط</button>
                <button id="new-scan">مسح مرة أخرى</button>
            </div>
        </div>
    </div>
    
    <footer>
        <!-- <p>تطبيق قارئ QR دون اتصال | لا يلزم وجود إنترنت</p> -->
    </footer>

    <script>
        // Initialize variables
        let html5Qrcode;
        let isScanning = false;
        
        // DOM Elements
        const startBtn = document.getElementById('start');
        const stopBtn = document.getElementById('stop');
        const showDiv = document.getElementById('show');
        const resultPara = document.getElementById('result');
        const statusText = document.getElementById('status');
        const copyBtn = document.getElementById('copy');
        const openBtn = document.getElementById('open');
        const newScanBtn = document.getElementById('new-scan');
        
        // Initialize the scanner
        function initScanner() {
            html5Qrcode = new Html5Qrcode("reader");
            
            // Set up camera selection if available
            Html5Qrcode.getCameras().then(cameras => {
                if (cameras && cameras.length > 1) {
                    const cameraSelect = document.createElement('select');
                    cameraSelect.id = 'reader__camera_selection';
                    
                    cameras.forEach(camera => {
                        const option = document.createElement('option');
                        option.value = camera.id;
                        option.text = camera.label || camera.id;
                        cameraSelect.appendChild(option);
                    });
                    
                    const dashboard = document.getElementById('reader__dashboard');
                    if (dashboard) {
                        dashboard.appendChild(cameraSelect);
                    }
                }
            }).catch(err => {
                console.error("Error getting cameras:", err);
            });
        }
        
        // Start scanning
        function startScanner() {
            if (isScanning) return;
            
            statusText.textContent = "جاري تشغيل الكاميرا...";
            
            // Get selected camera or use environment facing camera
            let cameraId = null;
            const cameraSelect = document.getElementById('reader__camera_selection');
            if (cameraSelect) {
                cameraId = cameraSelect.value;
            }
            
            const config = { 
                fps: 10, 
                qrbox: { width: 250, height: 250 },
                supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA]
            };
            
            const qrCodeSuccessCallback = (decodedText, decodedResult) => {
                if (decodedText) {
                    resultPara.textContent = decodedText;
                    showDiv.style.display = 'block';
                    showDiv.classList.add('visible');
                    statusText.textContent = "تم مسح رمز QR بنجاح!";
                    
                    // Stop scanning after successful scan
                    html5Qrcode.stop();
                    isScanning = false;
                    startBtn.disabled = false;
                    stopBtn.disabled = true;
                }
            };
            
            if (cameraId) {
                html5Qrcode.start(
                    cameraId, 
                    config, 
                    qrCodeSuccessCallback, 
                    () => {} // Verbose logging
                ).then(() => {
                    isScanning = true;
                    statusText.textContent = "جاري المسح... وجّه الكاميرا نحو رمز QR";
                    startBtn.disabled = true;
                    stopBtn.disabled = false;
                }).catch(err => {
                    statusText.textContent = "خطأ في بدء الماسح: " + err;
                    console.error(err);
                });
            } else {
                html5Qrcode.start(
                    { facingMode: "environment" }, 
                    config, 
                    qrCodeSuccessCallback, 
                    () => {} // Verbose logging
                ).then(() => {
                    isScanning = true;
                    statusText.textContent = "جاري المسح... وجّه الكاميرا نحو رمز QR";
                    startBtn.disabled = true;
                    stopBtn.disabled = false;
                }).catch(err => {
                    statusText.textContent = "خطأ في بدء الماسح: " + err;
                    console.error(err);
                });
            }
        }
        
        // Stop scanning
        function stopScanner() {
            if (!isScanning) return;
            
            html5Qrcode.stop().then(() => {
                isScanning = false;
                statusText.textContent = "تم إيقاف الماسح";
                startBtn.disabled = false;
                stopBtn.disabled = true;
            }).catch(err => {
                statusText.textContent = "خطأ في إيقاف الماسح: " + err;
                console.error(err);
            });
        }
        
        // Copy result to clipboard
        function copyToClipboard() {
            const text = resultPara.textContent;
            navigator.clipboard.writeText(text).then(() => {
                statusText.textContent = "تم النسخ إلى الحافظة!";
            }).catch(err => {
                statusText.textContent = "فشل النسخ: " + err;
            });
        }
        
        // Open URL if result is a valid URL
        function openResult() {
            const text = resultPara.textContent;
            if (text.startsWith('http://') || text.startsWith('https://')) {
                window.open(text, '_blank');
            } else {
                statusText.textContent = "النص الممسوح ليس عنوان URL صالحًا";
            }
        }
        
        // Start a new scan
        function newScan() {
            showDiv.style.display = 'none';
            showDiv.classList.remove('visible');
            startScanner();
        }
        
        // Set up event listeners
        document.addEventListener('DOMContentLoaded', () => {
            initScanner();
            
            startBtn.addEventListener('click', startScanner);
            stopBtn.addEventListener('click', stopScanner);
            copyBtn.addEventListener('click', copyToClipboard);
            openBtn.addEventListener('click', openResult);
            newScanBtn.addEventListener('click', newScan);
            
            // Auto-start the scanner
            startScanner();
        });
    </script>
    
    <script src="{{ asset('js/qrScript.js') }}"></script>
</body>
</html>