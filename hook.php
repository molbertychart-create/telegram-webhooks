<?php
// === Настройки через переменные окружения ===
$TELEGRAM_TOKEN = getenv('BOT_TOKEN'); 
$TELEGRAM_CHAT_ID = getenv('CHAT_ID'); 

// Тестовое сообщение
$text = "Тестовое уведомление от бота";

// Формируем URL и POST-поля
$url = "https://api.telegram.org/bot{$TELEGRAM_TOKEN}/sendMessage";
$post = ['chat_id' => $TELEGRAM_CHAT_ID, 'text' => $text];

// Отправляем сообщение через CURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);

// Проверяем ошибки
if (curl_errno($ch)) {
    echo "CURL Error: " . curl_error($ch);
} else {
    var_dump($response); // Проверка ответа Telegram
}

curl_close($ch);

