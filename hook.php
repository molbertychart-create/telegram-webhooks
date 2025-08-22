<?php
// === Настройки бота ===
$TOKEN   = "7298042093:AAGDuO6pU-AuH_Lz01oL1evByDp4yetBKuo"; 
$CHAT_ID = "5113963562";

// Получаем JSON от посредника AlfaCRM
$raw = file_get_contents("php://input");
$data = json_decode($raw, true);

// Вытаскиваем данные, которые реально приходят через molbertychart
$clientName  = $data['client']['name'] ?? $data['student']['name'] ?? 'Без имени';
$phone       = $data['client']['phone'] ?? $data['student']['phone'] ?? '—';
$service     = $data['record']['service'] ?? '—';
$employee    = $data['record']['employee'] ?? '—';
$branch      = $data['record']['branch'] ?? '—';
$datetime    = $data['record']['datetime'] ?? '—';
$event       = $data['event'] ?? 'Новая запись';

// Формируем сообщение
$msg = "📝 {$event}\nКлиент: {$clientName}\nТелефон: {$phone}\nУслуга: {$service}\nСотрудник: {$employee}\nФилиал: {$branch}\nКогда: {$datetime}";

// Отправка в Telegram
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

// Возвращаем AlfaCRM код 200 OK
http_response_code(200);
echo json_encode(['ok' => true]);
