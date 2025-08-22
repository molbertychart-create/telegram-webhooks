<?php
// === Настройки ===
$TELEGRAM_TOKEN = "7298042093:AAGDuO6pU-AuH_Lz01oL1evByDp4yetBKuo"; // 
твой токен
$TELEGRAM_CHAT_ID = "5113963562"; // твой chat_id

// Тестовое сообщение
$text = "Тестовое уведомление от бота";

$url = "https://api.telegram.org/bot{$TELEGRAM_TOKEN}/sendMessage";
$post = ['chat_id' => $TELEGRAM_CHAT_ID, 'text' => $text];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
if (curl_errno($ch)) {
    echo "CURL Error: " . curl_error($ch);
} else {
    var_dump($response); // Проверка ответа Telegram
}
curl_close($ch);

