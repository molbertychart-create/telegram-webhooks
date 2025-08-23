<?php
// === Настройки бота ===
$TOKEN   = "7298042093:AAGDuO6pU-AuH_Lz01oL1evByDp4yetBKuo"; // вставь свой токен
$CHAT_ID = "5113963562"; // вставь свой chat_id

// Читаем JSON от AlfaCRM
$raw = file_get_contents("php://input");
$data = json_decode($raw, true);

// Безопасно вытаскиваем поля (если их нет — ставим дефолт)
$clientName  = $data['client']['name']       ?? $data['student']['name'] ?? $data['lead']['name'] ?? 'Без имени';
$phone       = $data['client']['phone']      ?? $data['student']['phone'] ?? $data['lead']['phone'] ?? '—';
$service     = $data['record']['service']    ?? $data['lesson']['service'] ?? $data['service']['name'] ?? '—';
$email       = $data['client']['email']      ?? $data['student']['email'] ?? $data['lead']['email'] ?? '—';
$branch      = $data['record']['branch']     ?? $data['lesson']['branch'] ?? $data['branch']['name'] ?? '—';
$branch .= " (Щёлково/Королёв)";
$datetime    = $data['record']['datetime']   ?? $data['lesson']['datetime'] ?? ($data['date'] ?? '—');

// Название события
$event = $data['event'] ?? $data['action'] ?? 'Новая запись';

// Формируем сообщение для Telegram
$msg = "📝 {$event}\n"
     . "Клиент: {$clientName}\n"
     . "Телефон: {$phone}\n"
     . "Электронная почта: {$email}\n"
     . "Занятие/Мастер-класс: {$service}\n"
     . "Филиал: {$branch}\n"
     . "Когда: {$datetime}";

// Прикладываем отладку JSON, если нужно
if (strlen($raw) > 0) {
    $snippet = mb_substr($raw, 0, 700);
    $msg .= "\n\n— Отладка —\n" . $snippet;
}

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
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Возвращаем AlfaCRM "200 OK"
http_response_code(200);
header('Content-Type: application/json; charset=utf-8');
echo json_encode(['ok' => true, 'telegram_status' => $httpCode, 'telegram_response' => $response], JSON_UNESCAPED_UNICODE);
