<?php
// === Настройки бота ===
$TOKEN   = "7298042093:AAGDuO6pU-AuH_Lz01oL1evByDp4yetBKuo"; // твой токен
$CHAT_ID = "5113963562"; // твой chat_id

// Читаем JSON от AlfaCRM
$raw = file_get_contents("php://input");
$data = json_decode($raw, true);

// --- DEBUG (можно удалить после теста) ---
file_put_contents('debug.txt', json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// Безопасно вытаскиваем поля
$clientName  = $data['client']['name']       ?? $data['student']['name'] ?? $data['lead']['name'] ?? 'Без имени';
$phone       = $data['client']['phone']      ?? $data['student']['phone'] ?? $data['lead']['phone'] ?? '—';
$service     = $data['record']['service']    ?? $data['lesson']['service'] ?? $data['service']['name'] ?? '—';
$employee    = $data['record']['employee']   ?? $data['lesson']['teacher'] ?? $data['teacher']['name'] ?? '—';
$branch      = $data['record']['branch']     ?? $data['lesson']['branch'] ?? $data['branch']['name'] ?? '—';
$datetime    = $data['record']['datetime']   ?? $data['lesson']['datetime'] ?? ($data['date'] ?? '—');

// Название события
$event = $data['event'] ?? $data['action'] ?? 'Новая запись';

// Игнорируем пустые события
if ($clientName === 'Без имени' && $phone === '—') exit;

// Формируем сообщение для Telegram
$msg = "📝 {$event}\n"
     . "Клиент: {$clientName}\n"
     . "Телефон: {$phone}\n"
     . "Услуга: {$service}\n"
     . "Сотрудник: {$employee}\n"
     . "Филиал: {$branch}\n"
     . "Когда: {$datetime}";

// Отправляем сообщение в Telegram
$payload = [
    'chat_id' => $CHAT_ID,
    'text'    => $msg
];

$ch = curl_init("https://api.telegram.org/bot{$TOKEN}/sendMessage");
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_HTTPHEADER     => ['Content-Type: application/json; charset=utf-8'],
    CURLOPT_POSTFIELDS     => json_encode($payload, JSON_UNESCAPED_UNICODE)
]);
$response = curl_exec($ch);
curl_close($ch);

// Возвращаем AlfaCRM "200 OK"
http_response_code(200);
header('Content-Type: application/json; charset=utf-8');
echo json_encode(['ok' => true, 'telegram_response' => $response], JSON_UNESCAPED_UNICODE);
