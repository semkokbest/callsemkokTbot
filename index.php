<?php
$botToken = "8033950713:AAEZ3kIsJ_wlNTxhgghj7Wpybwx0rTv2vfw"; // Replace with your bot token
$apiURL = "https://api.telegram.org/bot$botToken/";

$update = json_decode(file_get_contents("php://input"), TRUE);

if (isset($update['message'])) {
    $chatId = $update['message']['chat']['id'];
    $text = $update['message']['text'];

    // Load the CSV file
    $csvFile = 'data.csv'; // Path to your CSV file
    $searchValue = strtolower($text); // The search term from the user
    $found = false;
    $responseText = '';

    // Read the CSV file
    if (($handle = fopen($csvFile, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            // Assuming the value you want to search is in the first column
            if (stripos($data[0], $searchValue) !== FALSE) {
                $responseText .= "Found: " . implode(", ", $data) . "\n";
                $found = true;
            }
        }
        fclose($handle);
    }

    // If not found
    if (!$found) {
        $responseText = "No results found for '$searchValue'.";
    }

    // Send response back to the user
    file_get_contents($apiURL . "sendMessage?chat_id=$chatId&text=" . urlencode($responseText));
}
?>