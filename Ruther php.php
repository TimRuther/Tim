<?php
// PHP-Fehlerberichte unterdrücken, um JSON-Ausgabe sauber zu halten
ini_set('display_errors', 0);
error_reporting(0);

session_start();

// Standard-Antwortstruktur
$response = [
    'success' => false,
    'message' => 'Ungültige Anfrage.'
];

// Funktion zum Senden der JSON-Antwort und Beenden
function sendJsonResponse($data) {
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// --- KONFIGURATION ---
const RECIPIENT_EMAIL = "Ruther.Tim1@gmail.com";
const MAX_SENDS_PER_DAY = 1; // Kann auf 1 bleiben oder entfernt werden, wenn nicht gewünscht.
// ----------------------

// 1. Überprüfen, ob die Anfrage per POST gesendet wurde
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $currentDate = date('Y-m-d');

    // 2. Tägliche Sende-Begrenzung prüfen (Anti-Spam über Session)
    if (MAX_SENDS_PER_DAY > 0 && isset($_SESSION['last_sent_date']) && $_SESSION['last_sent_date'] === $currentDate) {
        $response['message'] = "Sie können nur einmal pro Tag eine Nachricht senden.";
        sendJsonResponse($response);
    }

    // 3. Eingaben sichern und validieren
    $name    = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
    $email   = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS);

    // Trimmen (Leerräume entfernen)
    $name    = trim($name);
    $message = trim($message);

    // Validierungen
    if (empty($message)) {
        $response['message'] = "Die Nachricht darf nicht leer sein.";
        sendJsonResponse($response);
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = "Ungültige E-Mail-Adresse.";
        sendJsonResponse($response);
    }
    
    // 4. E-Mail zusammenstellen
    $subject = "Kontaktformular Nachricht von $name";
    
    $body = "Name: " . ($name ?: 'Nicht angegeben') . "\n";
    $body .= "E-Mail: $email\n\n";
    $body .= "Nachricht:\n$message";
    
    // Header für plain text und Reply-To
    $headers = "From: " . $email . "\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    
    // 5. E-Mail senden
    if (mail(RECIPIENT_EMAIL, $subject, $body, $headers)) {
        // Erfolg: Datum der letzten gesendeten Nachricht speichern
        $_SESSION['last_sent_date'] = $currentDate;
        $response['success'] = true;
        $response['message'] = "Ihre Nachricht wurde gesendet. Vielen Dank!";
    } else {
        // Fehler beim Senden
        $response['message'] = "Es gab ein Problem beim Senden Ihrer Nachricht. Bitte versuchen Sie es später erneut.";
    }
}

// 6. JSON-Antwort senden (passiert entweder hier oder früher bei einem Fehler)
sendJsonResponse($response);
?>
