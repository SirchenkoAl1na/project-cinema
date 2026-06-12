<?php
/**
 * Контролер сканера QR-квитків.
 * Адаптований для проєкту без Laravel (чистий PHP + PDO через клас DB).
 *
 * ПІДКЛЮЧЕННЯ:
 * 1. Додайте цей файл у App/Controllers/ScannerController.php
 * 2. У routes.php додайте два маршрути (дивіться нижче)
 * 3. Виконайте SQL-міграцію з файлу migration_add_qr_to_tickets.sql
 *
 * МАРШРУТИ (додати в routes.php):
 *   $this->get('/admin/scanner', [ScannerController::class, 'index'], UserType::ADMIN);
 *   $this->post('/admin/scanner/validate', [ScannerController::class, 'validate'], UserType::ADMIN);
 */

namespace App\Controllers;

use App\DB;
use App\Services\AchievementService;
use App\Models\Ticket;

class ScannerController extends Controller
{
    /**
     * GET /admin/scanner
     * Відображає сторінку сканера QR-кодів.
     */
    public function index(): void
    {
        self::render('Сканер квитків', '/admin/scanner', 'admin');
    }

    /**
     * POST /admin/scanner/validate
     * Приймає qr_token з тіла JSON-запиту, валідує квиток, повертає JSON-відповідь.
     */
    public function validate(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $body      = file_get_contents('php://input');
        $data      = json_decode($body, true);
        $qr_token  = trim($data['qr_token'] ?? '');

        if (empty($qr_token)) {
            echo json_encode(['valid' => false, 'reason' => 'empty', 'message' => 'QR-токен не передано']);
            return;
        }

        $ticket = DB::selectOne(
            'tickets t
             JOIN places pl ON pl.id = t.place_id
             JOIN sales  s  ON s.id  = t.sale_id
             JOIN seanses se ON se.id = s.seanse_id
             JOIN films  f  ON f.id  = se.film_id
             JOIN holes  h  ON h.id  = se.hole_id
             LEFT JOIN users u ON u.id = s.user_id',
            't.id, t.qr_token, t.qr_status, t.scanned_at, t.scanned_by_name,
             pl.`row`, pl.place AS seat,
             f.title AS movie,
             h.nomer AS hall,
             COALESCE(u.full_name, \'Гість\') AS buyer',
            "t.qr_token = '" . addslashes($qr_token) . "'"
        );

        if (!$ticket) {
            echo json_encode(['valid' => false, 'reason' => 'not_found', 'message' => 'Квиток не знайдено. Перевірте QR-код']);
            return;
        }

        if ($ticket['qr_status'] === 'scanned') {
            echo json_encode([
                'valid'       => false,
                'reason'      => 'already_scanned',
                'message'     => 'Квиток вже було використано',
                'scanned_at'  => $ticket['scanned_at'],
                'scanned_by'  => $ticket['scanned_by_name'],
                'ticket_info' => [
                    'movie' => $ticket['movie'],
                    'hall'  => $ticket['hall'],
                    'row'   => $ticket['row'],
                    'seat'  => $ticket['seat'],
                    'buyer' => $ticket['buyer'],
                ],
            ]);
            return;
        }

        $admin_name = $_SESSION['user']['full_name'] ?? 'Адмін';
        $zone = new \DateTimeZone('Europe/Kiev');
        $date = new \DateTime('now', $zone);

        $now= $date->format('Y-m-d H:i');

        DB::update('tickets', "id = {$ticket['id']}", [
            'qr_status'       => 'scanned',
            'scanned_at'      => $now,
            'scanned_by_name' => $admin_name,
        ]);
        echo json_encode([
            'valid'       => true,
            'message'     => 'Квиток дійсний. Вхід дозволено',
            'ticket_info' => [
                'hall' => $ticket['hall'],
                'row'  => $ticket['row'],
                'seat' => $ticket['seat'],
            ],
        ]);
        $ticket=new Ticket($ticket['id']);
        AchievementService::checkAchievements($ticket->sale_id,$ticket->sale->user_id);
    }
}
