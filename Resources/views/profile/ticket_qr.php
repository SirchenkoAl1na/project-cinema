<div class="block column" id="profile_qr" style="max-width: 600px; margin: 0 auto;">
    <div class="row" style="margin-bottom: 20px;">
        <button class="button" onclick="location.href='/profile'" style="display:flex;align-items:center;gap:10px;">
            <i class="fa-solid fa-arrow-left"></i> В профіль
        </button>
        <h3 style="margin-left: auto;">QR-квиток</h3>
    </div>

    <?php if ($ticket->qr_status === 'scanned'): ?>
        <div style="background-color: #27ae60; color: #fff; padding: 15px; border-radius: 8px; text-align: center; margin-bottom: 20px;">
            <h3>✅ Квиток використано</h3>
            <p>Час сканування: <?= htmlspecialchars($ticket->scanned_at) ?></p>
            <p>Контролер: <?= htmlspecialchars($ticket->scanned_by_name) ?></p>
        </div>
    <?php endif; ?>

    <div style="background: #2a2a2a; border-radius: 12px; padding: 30px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
        <?php
        $film = $ticket->sale->seanse->film->title ?? 'Невідомий фільм';
        $hole = $ticket->sale->seanse->hole->nomer ?? 'Невідомий зал';
        $date = \App\Data::datetimeFormat($ticket->sale->seanse->date, $ticket->sale->seanse->time);
        $qr_token = $ticket->qr_token;
        ?>

        <h2 style="color: #e50914; margin-bottom: 5px;"><?= htmlspecialchars($film) ?></h2>
        <p style="color: #aaa; margin-bottom: 20px;"><?= htmlspecialchars($date) ?></p>

        <div style="display: inline-block; background: #fff; padding: 15px; border-radius: 12px; margin-bottom: 20px;">
        <?php
        if ($qr_token) {
            try {
                $options = new \chillerlan\QRCode\QROptions([
                    'outputInterface' => \chillerlan\QRCode\Output\QRMarkupSVG::class,
                    'eccLevel'        => \chillerlan\QRCode\Common\EccLevel::H,
                ]);
                $qr_base64 = (new \chillerlan\QRCode\QRCode($options))->render($qr_token);
                echo '<img src="'.$qr_base64.'" alt="QR code" style="width:300px; height:300px;">';
            } catch (\Exception $e) {
                echo '<p style="color:red">Помилка генерації QR-коду</p>';
            }
        } else {
            echo '<p style="color:red">QR-токен відсутній</p>';
        }
        ?>
        </div>

        <div style="font-size: 1.1em; color: #ddd;">
            <p style="margin: 5px 0;">Зал: <b><?= htmlspecialchars($hole) ?></b></p>
            <p style="margin: 5px 0;">Ряд: <b><?= htmlspecialchars($ticket->place->row) ?></b>, Місце: <b><?= htmlspecialchars($ticket->place->place) ?></b></p>
            <p style="margin: 5px 0;">Ціна: <b><?= htmlspecialchars($ticket->price) ?> грн</b></p>
        </div>
    </div>
</div>
