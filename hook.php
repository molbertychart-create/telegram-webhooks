cat > hook.php << 'EOF'
<?php
// Telegram bot credentials
$botToken = "7298042093:AAGDuO6pU-AuH_Lz01oL1evByDp4yetBKuo";
$chatId = "5113963562";

// Получаем данные от CRM
$input = file_get_contents("php://input");
$data = json_decode($input, true);

// Извлекаем данные с подстраховкой
$client = $data['client'] ?? "Без имени";
$phone = $data['phone'] ?? "—";
$email = $data['email'] ?? "—";
$service = $data['service'] ?? "—";
$branch = $data['branch'] ?? "—";
$date = $data['date'] ?? "—";

// Добавляем пояснение филиала
if (stripos($branch, 'Щёлково') !== false) {
    $branch .= " (Щёлково)";
} elseif (stripos($branch, 'Корол') !== false) { // корень "Корол" — чтобы ловить Королёв/Королев
    $branch .= " (Королёв)";
}

// Формируем текст сообщения
$message = "📝 Новая запись\n";
$message .= "Клиент: $client\n";
$message .= "Телефон: $phone\n";
$message .= "Электронная почта: $email\n";
$message .= "Занятие/Мастер-класс: $service\n";
$message .= "Филиал: $branch\n";
$message .= "Когда: $date";

// Отправка в Telegram
$sendUrl = "https://api.telegram.org/bot$botToken/sendMessage";
$params = [
    "chat_id" => $chatId,
    "text" => $message,
    "parse_mode" => "HTML"
];

file_get_contents($sendUrl . "?" . http_build_query($params));
?>
EOF
