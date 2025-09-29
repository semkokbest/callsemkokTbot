<?php
$botToken = "8067216649:AAEZvarxLMX50OlODOX9PAKlc6xKiWKww1Y";
$apiUrl = "https://api.telegram.org/bot$botToken";

// Read the CSV file into an array
function readCsv($filename) {
    $data = [];
    if (($handle = fopen($filename, "r")) !== FALSE) {
        $header = fgetcsv($handle);
        while (($row = fgetcsv($handle)) !== FALSE) {
            $data[] = array_combine($header, $row);
        }
        fclose($handle);
    }
    return $data;
}

// Send message to Telegram
function sendMessage($chatId, $message) {
    global $apiUrl;
    $url = "$apiUrl/sendMessage?chat_id=$chatId&text=" . urlencode($message)."&parse_mode=html";
    file_get_contents($url);
}

// Main logic
$data = readCsv("data.csv");
 $th= file_get_contents('php://input');
$update=json_decode($th);
if (isset($update["message"]["text"])) {
       $chat_id = $update["message"]["chat"]["id"];
    $text = $update["message"]["text"];

if($text== "/start"){
$response ="Welcome to Federal Housing Corporation Telegram channel for viewing commercial/residential auction results.";
}
else{
// Search in the CSV data
    $found = array_filter($data, function($row) use ($text) {
        return stripos($row["name"], $text) !== false; // Case-insensitive search
    });

    if ($found) {
        $response .= "Results:\n";
        foreach ($found as $row) {
            $response .= "Name: {$row['name']}, Email: {$row['email']}, Phone: {$row['phone']}\n";
        }
    } else {
        $response = "No results found for '$text'.";
    }
}
  // Send response back to Telegram
   sendMessage($chatId, $response); 
}


?>
