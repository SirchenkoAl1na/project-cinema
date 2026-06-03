<?php
/**
 * Сторінка сканера QR-квитків для адміністратора.
 * Адаптована для проєкту без Laravel (чистий PHP + PDO через клас DB).
 *
 * Підключається через layouts/admin.php.
 * Ендпоінт валідації: POST /admin/scanner/validate (ScannerController::validate)
 *
 * CSRF-захист: у цьому проєкті відсутній стандартний CSRF-токен,
 * тому API-ендпоінт перевіряє наявність активної сесії адміністратора на сервері.
 */
?>

<style>
    @media (max-width: 768px) {
        h1 { font-size: 1.5em; text-align: center; }
        #result { font-size: .9em; padding: 15px; }
    }
    @keyframes pulse-border {
        0%   { border-color: #333; }
        50%  { border-color: #e50914; }
        100% { border-color: #333; }
    }
    #scanner-wrapper.scanning { animation: pulse-border 2s ease-in-out infinite; }
    #scanner-wrapper.success  { border-color: #4caf50 !important; animation: none; }
    #scanner-wrapper.error    { border-color: #e50914 !important; animation: none; }

    #scan-again-btn {
        margin-top: 15px; padding: 12px 30px;
        background: #e50914; color: #fff;
        border: none; border-radius: 10px;
        font-size: 1em; cursor: pointer; display: none;
        transition: background .2s;
    }
    #scan-again-btn:hover { background: #c0070f; }

    #countdown { font-size: .8em; color: #888; margin-top: 8px; min-height: 20px; }

    .scanner-card {
        max-width: 600px; margin: 0 auto;
        background: #1e1e1e; padding: 20px;
        border-radius: 10px; text-align: center;
        color: #fff;
    }
</style>

<div class="scanner-card">

    <div id="scanner-wrapper" class="scanning"
         style="width:100%;max-width:500px;margin:0 auto;overflow:hidden;border-radius:12px;border:2px solid #333;transition:border-color .3s;">
        <div id="reader" style="width:100%;height:auto;"></div>
    </div>

    <div id="result"
         style="margin-top:15px;padding:15px;font-weight:bold;border-radius:10px;display:none;font-size:1em;line-height:1.6;">
    </div>

    <div id="countdown"></div>

    <button id="scan-again-btn" onclick="restartScanner()">
        📷 Сканувати наступний
    </button>

</div>

<!-- Бібліотека зчитування QR з камери (без npm, через CDN) -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
// =========================================================================
// Сканер QR-квитків — адаптований для чистого PHP-проєкту
// Ендпоінт: POST /admin/scanner/validate  (повертає JSON)
// =========================================================================

document.addEventListener('DOMContentLoaded', () => {
    const html5QrCode = new Html5Qrcode("reader");
    const resultDiv    = document.getElementById('result');
    const wrapper      = document.getElementById('scanner-wrapper');
    const scanAgainBtn = document.getElementById('scan-again-btn');
    const countdownDiv = document.getElementById('countdown');

    let isScanning   = false;
    let isProcessing = false; // Захист від подвійного спрацьовування
    let restartTimer = null;
    let cntTimer     = null;

    const config = { fps: 10, qrbox: { width: 250, height: 250 } };

    // --- Запуск / зупинка камери ---
    function startScanner() {
        resultDiv.style.display    = 'none';
        scanAgainBtn.style.display = 'none';
        countdownDiv.innerText     = '';
        wrapper.className          = 'scanning';

        html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess)
            .then(() => { isScanning = true; isProcessing = false; })
            .catch(err => console.error('Scanner start error:', err));
    }

    function stopScanner() {
        if (!isScanning) return Promise.resolve();
        return html5QrCode.stop()
            .then(() => { isScanning = false; })
            .catch(() => { isScanning = false; });
    }

    // --- Відображення результату ---
    // autoRestart=true  → через 4 сек камера перезапускається (для успішного скану)
    // autoRestart=false → без таймера (адмін читає деталі стільки, скільки треба)
    function showResult(isValid, html, autoRestart = true) {
        resultDiv.style.display    = 'block';
        resultDiv.style.background = isValid ? '#1a3d1a' : '#3d1a1a';
        resultDiv.style.border     = `1px solid ${isValid ? '#4caf50' : '#e50914'}`;
        resultDiv.style.color      = isValid ? '#7ddb7d' : '#ff7070';
        resultDiv.innerHTML        = html;
        wrapper.className          = isValid ? 'success' : 'error';
        scanAgainBtn.style.display = 'inline-block';

        clearInterval(cntTimer);
        clearTimeout(restartTimer);

        if (autoRestart) {
            let secs = 4;
            const tick = () => { countdownDiv.innerText = `Автоматичне сканування через ${secs}...`; };
            tick();
            cntTimer = setInterval(() => {
                secs--;
                if (secs > 0) tick(); else clearInterval(cntTimer);
            }, 1000);
            restartTimer = setTimeout(restartScanner, 4000);
        } else {
            countdownDiv.innerText = '';
        }
    }

    window.restartScanner = function () {
        clearTimeout(restartTimer);
        clearInterval(cntTimer);
        stopScanner().then(startScanner);
    };

    const onScanSuccess = async (decodedText) => {
        if (isProcessing) return;  
        isProcessing = true;

        await stopScanner();

        try {
            const response = await fetch('/admin/scanner/validate', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept':       'application/json',
                },
                body: JSON.stringify({ qr_token: decodedText })
            });

            const data = await response.json();

            if (data.valid) {
                showResult(true,
                    `<div style="text-align:left;">
                        <div style="font-size:1.1em;margin-bottom:12px;">✅ <strong>${data.message}</strong></div>
                        <table style="width:100%;border-collapse:collapse;font-size:.9em;font-weight:normal;">
                            <tr><td style="padding:5px 0;color:#aaa;width:45%;">Зал</td><td><b>${data.ticket_info.hall}</b></td></tr>
                            <tr><td style="padding:5px 0;color:#aaa;">Ряд</td><td><b>${data.ticket_info.row}</b></td></tr>
                            <tr><td style="padding:5px 0;color:#aaa;">Місце</td><td><b>${data.ticket_info.seat}</b></td></tr>
                        </table>
                    </div>`,
                    true
                );

            } else if (data.reason === 'already_scanned') {
                showResult(false,
                    `<div style="text-align:left;">
                        <div style="font-size:1.1em;margin-bottom:12px;">❌ <strong>${data.message}</strong></div>
                        <table style="width:100%;border-collapse:collapse;font-size:.9em;font-weight:normal;color:#ffaaaa;">
                            <tr style="border-bottom:1px solid rgba(255,100,100,.2)">
                                <td style="padding:6px 0;color:#ff9999;width:45%;">🕐 Відскановано</td>
                                <td><b>${data.scanned_at ?? '—'}</b></td>
                            </tr>
                            <tr style="border-bottom:1px solid rgba(255,100,100,.2)">
                                <td style="padding:6px 0;color:#ff9999;">👤 Контролер</td>
                                <td><b>${data.scanned_by ?? '—'}</b></td>
                            </tr>
                            <tr><td colspan="2" style="padding:4px 0;border-bottom:1px solid rgba(255,100,100,.3)"></td></tr>
                            <tr style="border-bottom:1px solid rgba(255,100,100,.2)">
                                <td style="padding:6px 0;color:#ff9999;">🎬 Фільм</td>
                                <td><b>${data.ticket_info.movie}</b></td>
                            </tr>
                            <tr style="border-bottom:1px solid rgba(255,100,100,.2)">
                                <td style="padding:6px 0;color:#ff9999;">🏛 Зал</td>
                                <td><b>${data.ticket_info.hall}</b></td>
                            </tr>
                            <tr style="border-bottom:1px solid rgba(255,100,100,.2)">
                                <td style="padding:6px 0;color:#ff9999;">💺 Ряд / Місце</td>
                                <td><b>${data.ticket_info.row} / ${data.ticket_info.seat}</b></td>
                            </tr>
                            <tr>
                                <td style="padding:6px 0;color:#ff9999;">🙍 Покупець</td>
                                <td><b>${data.ticket_info.buyer}</b></td>
                            </tr>
                        </table>
                    </div>`,
                    false  
                );

            } else {
                showResult(false, `❌ <strong>${data.message}</strong>`, false);
            }

        } catch (err) {
            console.error('Scan error:', err);
            showResult(false, `❌ <strong>Помилка з'єднання з сервером</strong><div style="font-size:0.8em;color:#ffaaaa;margin-top:5px;">Подробиці в консолі браузера</div>`, false);
        }
    };

    startScanner();
});
</script>
