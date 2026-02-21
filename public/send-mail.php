<?php
/**
 * Scornet Avocat — Contact Form Handler
 * Sends email from the contact form to the office email.
 */

// Configuration
$to = 'contact@scornet-avocat.fr';
$subject_prefix = '[Site Web] ';

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}

// Get and sanitize form data
$lastname  = htmlspecialchars(trim($_POST['lastname'] ?? ''));
$firstname = htmlspecialchars(trim($_POST['firstname'] ?? ''));
$email     = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$phone     = htmlspecialchars(trim($_POST['phone'] ?? ''));
$subject   = htmlspecialchars(trim($_POST['subject'] ?? 'Contact'));
$message   = htmlspecialchars(trim($_POST['message'] ?? ''));

// Validate required fields
if (empty($lastname) || empty($firstname) || empty($email) || empty($message)) {
    http_response_code(400);
    exit('Missing required fields');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    exit('Invalid email address');
}

// Build email
$full_subject = $subject_prefix . $subject . ' — ' . $firstname . ' ' . $lastname;

$body = "Nouveau message depuis le site web scornet-avocat.fr\n";
$body .= "================================================\n\n";
$body .= "Nom : $lastname\n";
$body .= "Prénom : $firstname\n";
$body .= "Email : $email\n";
$body .= "Téléphone : " . ($phone ?: 'Non renseigné') . "\n";
$body .= "Objet : $subject\n\n";
$body .= "Message :\n";
$body .= "--------\n";
$body .= "$message\n";

$headers = "From: noreply@scornet-avocat.fr\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// Send email
$sent = mail($to, $full_subject, $body, $headers);

if ($sent) {
    http_response_code(200);
    echo 'OK';
} else {
    http_response_code(500);
    echo 'Failed to send email';
}
