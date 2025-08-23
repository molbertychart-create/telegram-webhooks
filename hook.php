<?php
// hook.php

// Настройки
$token = "7298042093:AAGDuO6pU-AuH_Lz01oL1evByDp4yetBKuo";
$chat_id = "5113963562";

// Читаем данные из запроса
$data = file_get_contents("php://input");
file_put_contents("debug.json", $data); // сохраняем для отладки

$update = json_decode($data, true);

// Извлекаем данные
$client = $update['client']['name'] ?? 'Без имени';
$phone = $update['client']['phone'] ?? '—';
$email = $update['client']['email'] ?? '—';
$service = $update['service']['name'] ?? '—';
$branch = $update['branch']['name'] ?? '—';
$date = $update['date'] ?? '—';

// Формируем сообщение
$message = "📝 Новая запись\n";
$message .= "Клиент: $client\n";
$message .= "Телефон: $phone\n";
$message .= "Электронная почта: $email\n";
$message .= "Занятие/Мастер-класс: $service\n";
$message .= "Филиал (Щёлково/Королёв): $branch\n";
$message .= "Когда: $date";

// Отправляем в Telegram
$url = "https://api.telegram.org/bot$token/sendMessage";
$params = [
    "chat_id" => $chat_id,
    "text" => $message,
    "parse_mode" => "HTML"
];

$options = [
    "http" => [
        "header"  => "Content-type: 
application/x-www-form-urlencoded\r\n",
        "method"  => "POST",
        "content" => http_build_query($params)
    ]
];

$context  = stream_context_create($options);
file_get_contents($url, false, $context);
?>
<?php
// Логируем все входящие запросы в debug.txt
$input = file_get_contents('php://input');
file_put_contents(__DIR__ . '/debug.txt', $input . PHP_EOL, FILE_APPEND);

$data = json_decode($input, true);

// Твой токен бота и chat_id
$botToken = "ВАШ_ТОКЕН_БОТА";   // 👉 сюда впиши токен, который BotFather выдал
$chatId   = "ВАШ_CHAT_ID";      // 👉 сюда впиши свой chat_id (например, 123456789)

// Достаём данные с проверкой
$clientName  = $data['name'] ?? 'Без имени';
$phone       = $data['phone'] ?? '—';
$email       = $data['email'] ?? '—';
$service     = $data['service'] ?? '—';
$branch      = $data['branch'] ?? '—';
$datetime    = $data['datetime'] ?? '—';

// Формируем сообщение
$message = "📝 Новая запись\n";
$message .= "Клиент: {$clientName}\n";
$message .= "Телефон: {$phone}\n";
$message .= "Электронная почта: {$email}\n";
$message .= "Занятие/Мастер-класс: {$service}\n";
$message .= "Филиал (Щёлково/Королёв): {$branch}\n";
$message .= "Когда: {$datetime}";

// Отправляем в Telegram
file_get_contents("https://api.telegram.org/bot{$botToken}/sendMessage?" . http_build_query([
    "chat_id" => $chatId,
    "text"    => $message,
]));
