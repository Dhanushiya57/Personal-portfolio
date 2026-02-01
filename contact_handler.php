<?php
// Start session and include config
session_start();
require_once 'config.php';

// Set headers for AJAX
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse(false, 'Invalid request method');
}

// Get POST data (support both form data and JSON)
$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

if (stripos($contentType, 'application/json') !== false) {
    $input = json_decode(file_get_contents('php://input'), true);
} else {
    $input = $_POST;
}

// Validate required fields
$requiredFields = ['name', 'email', 'subject', 'message'];
foreach ($requiredFields as $field) {
    if (!isset($input[$field]) || empty(trim($input[$field]))) {
        sendJsonResponse(false, "Please fill in all required fields: {$field}");
    }
}

// Sanitize inputs
$name = sanitizeInput($input['name']);
$email = sanitizeInput($input['email']);
$subject = sanitizeInput($input['subject']);
$message = sanitizeInput($input['message']);

// Validate email
if (!isValidEmail($email)) {
    sendJsonResponse(false, 'Please provide a valid email address');
}

// Validate message length
if (strlen($message) < 10) {
    sendJsonResponse(false, 'Message must be at least 10 characters long');
}

if (strlen($message) > 5000) {
    sendJsonResponse(false, 'Message is too long (maximum 5000 characters)');
}

// Rate limiting - prevent spam
if (!isset($_SESSION['last_contact_time'])) {
    $_SESSION['last_contact_time'] = 0;
}

$timeSinceLastContact = time() - $_SESSION['last_contact_time'];
if ($timeSinceLastContact < 60) { // 60 seconds cooldown
    $remainingTime = 60 - $timeSinceLastContact;
    sendJsonResponse(false, "Please wait {$remainingTime} seconds before sending another message");
}

// Get visitor information
$ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';

// Save to database
$conn = getDBConnection();
if ($conn) {
    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, subject, message, ip_address, user_agent) VALUES (?, ?, ?, ?, ?, ?)");
    
    if ($stmt) {
        $stmt->bind_param("ssssss", $name, $email, $subject, $message, $ipAddress, $userAgent);
        
        if (!$stmt->execute()) {
            error_log("Database insert failed: " . $stmt->error);
            // Continue anyway - don't fail if database insert fails
        }
        
        $stmt->close();
    }
    
    $conn->close();
}

// Send email notification
$emailSent = sendContactEmail($name, $email, $subject, $message);

// Update session
$_SESSION['last_contact_time'] = time();

// Send response
if ($emailSent) {
    sendJsonResponse(true, 'Thank you for your message! I will get back to you soon.', [
        'timestamp' => date('Y-m-d H:i:s')
    ]);
} else {
    sendJsonResponse(true, 'Your message has been saved. I will review it soon.', [
        'timestamp' => date('Y-m-d H:i:s')
    ]);
}

/**
 * Send contact email
 */
function sendContactEmail($name, $email, $subject, $message) {
    $to = ADMIN_EMAIL;
    $emailSubject = "New Contact Form Submission: " . $subject;
    
    // Create HTML email
    $htmlMessage = "
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; padding: 20px; border-radius: 5px; }
            .content { background: #f9f9f9; padding: 20px; border-radius: 5px; margin-top: 20px; }
            .info-row { margin: 10px 0; padding: 10px; background: white; border-radius: 3px; }
            .label { font-weight: bold; color: #6366f1; }
            .message-box { background: white; padding: 15px; border-left: 4px solid #6366f1; margin-top: 15px; }
            .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>New Contact Form Submission</h2>
            </div>
            <div class='content'>
                <div class='info-row'>
                    <span class='label'>From:</span> {$name}
                </div>
                <div class='info-row'>
                    <span class='label'>Email:</span> {$email}
                </div>
                <div class='info-row'>
                    <span class='label'>Subject:</span> {$subject}
                </div>
                <div class='info-row'>
                    <span class='label'>Date:</span> " . date('F j, Y, g:i a') . "
                </div>
                <div class='message-box'>
                    <div class='label'>Message:</div>
                    <p>" . nl2br($message) . "</p>
                </div>
            </div>
            <div class='footer'>
                <p>This email was sent from your portfolio contact form</p>
                <p><a href='" . SITE_URL . "'>Visit Website</a></p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    // Create plain text version
    $textMessage = "New Contact Form Submission\n\n";
    $textMessage .= "From: {$name}\n";
    $textMessage .= "Email: {$email}\n";
    $textMessage .= "Subject: {$subject}\n";
    $textMessage .= "Date: " . date('F j, Y, g:i a') . "\n\n";
    $textMessage .= "Message:\n{$message}\n\n";
    $textMessage .= "---\nThis email was sent from your portfolio contact form\n";
    
    // Email headers
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: " . SITE_NAME . " <noreply@" . $_SERVER['HTTP_HOST'] . ">\r\n";
    $headers .= "Reply-To: {$name} <{$email}>\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    // Send email
    $result = @mail($to, $emailSubject, $htmlMessage, $headers);
    
    // Log if email fails
    if (!$result) {
        error_log("Failed to send contact form email to: {$to}");
    }
    
    return $result;
}
?>
