<?php
session_start();

// E-Mail Adresse, an die die Nachrichten gesendet werden sollen
$to = "Ruther.Tim1@gmail.com"; // Ersetze dies durch deine E-Mail-Adresse

// Überprüfen, ob der Benutzer bereits heute eine E-Mail gesendet hat
$currentDate = date('Y-m-d');
if (isset($_SESSION['last_sent_date']) && $_SESSION['last_sent_date'] == $currentDate) {
    echo "Sie können nur einmal pro Tag eine Nachricht senden.";
    exit;
}

// Formular-Daten verarbeiten
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Eingaben sichern
    $name = htmlspecialchars(trim($_POST["name"]));
    $email = htmlspecialchars(trim($_POST["email"]));
    $message = htmlspecialchars(trim($_POST["message"]));
    
    // Überprüfen, ob die Nachricht leer ist
    if (empty($message)) {
        echo "Die Nachricht darf nicht leer sein.";
        exit;
    }

    // E-Mail-Adresse validieren
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Ungültige E-Mail-Adresse.";
        exit;
    }

    // E-Mail-Betreff
    $subject = "Kontaktformular Nachricht von $name";
    
    // E-Mail-Header
    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    
    // E-Mail-Body
    $body = "Name: $name\nE-Mail: $email\n\nNachricht:\n$message";
    
    // E-Mail senden
    if (mail($to, $subject, $body, $headers)) {
        // Datum der letzten gesendeten Nachricht speichern
        $_SESSION['last_sent_date'] = $currentDate;
        echo "Ihre Nachricht wurde gesendet. Vielen Dank!";
    } else {
        echo "Es gab ein Problem beim Senden Ihrer Nachricht. Bitte versuchen Sie es später erneut.";
    }
} else {
    echo "Ungültige Anfrage.";
}
?>
    $email\n\nNachricht:\n$message";
    
    // E-Mail senden
    if (mail($to, $subject, $body, $headers)) {
        // Datum der letzten gesendeten Nachricht speichern
        $_SESSION['last_sent_date'] = $currentDate;
        echo "Ihre Nachricht wurde gesendet. Vielen Dank!";
    } else {
        echo "Es gab ein Problem beim Senden Ihrer Nachricht. Bitte versuchen Sie es später erneut.";
    }
} else {
    echo "Ungültige Anfrage.";
}
?>
    $email\n\nNachricht:\n$message";
    
    // E-Mail senden
    if (mail($to, $subject, $body, $headers)) {
        // Datum der letzten gesendeten Nachricht speichern
        $_SESSION['last_sent_date'] = $currentDate;
        echo "Ihre Nachricht wurde gesendet. Vielen Dank!";
    } else {
        echo "Es gab ein Problem beim Senden Ihrer Nachricht. Bitte versuchen Sie es später erneut.";
    }
} else {
    echo "Ungültige Anfrage.";
}
?>
