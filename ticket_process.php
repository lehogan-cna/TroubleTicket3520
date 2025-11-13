<?php
session_start();

// Verify all required data is present
if (!isset($_SESSION['form_data']['first_name']) || 
    !isset($_SESSION['form_data']['title'])) {
    header('Location: index.php');
    exit;
}

// File path for XML
$xml_file = 'data/tickets.xml';

// Create data directory if it doesn't exist
if (!file_exists('data')) {
    mkdir('data', 0777, true);
}

// Function to generate unique ID
function generateUserId() {
    return 'TID_' . time() . '_' . rand(1000, 9999);
}

// Load existing XML or create new one
if (file_exists($xml_file)) {
    $xml = simplexml_load_file($xml_file);
} else {
    // Create new XML document
    $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><tickets></tickets>');
}

// Create new user element
$user = $xml->addChild('ticket');
$user->addAttribute('id', generateUserId());
$user->addAttribute('registered', date('Y-m-d H:i:s'));

// Add user data from session
$user->addChild('first_name', $_SESSION['form_data']['first_name']);
$user->addChild('last_name', $_SESSION['form_data']['last_name']);
$user->addChild('date_submitted', $_SESSION['form_data']['date_submitted']);
$user->addChild('email', $_SESSION['form_data']['email']);
$user->addChild('user_type', $_SESSION['form_data']['user_type']);
$user->addChild('title', $_SESSION['form_data']['title']);
$user->addChild('ticket_info', $_SESSION['form_data']['ticket_info']);
$user->addChild('priority', $_SESSION['form_data']['priority']);
$user->addChild('status', $_SESSION['form_data']['status']);


// Format and save XML
$dom = new DOMDocument('1.0');
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;
$dom->loadXML($xml->asXML());
$dom->save($xml_file);

// Clear session data
$registered_user = $_SESSION['form_data']['first_name'] . ' ' . $_SESSION['form_data']['last_name'];
//unset($_SESSION['form_data']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STAFF - Submission Complete</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Submission Successful!</h2>

        <div class="success">
            <p><strong>Thank you for submitting a ticket, <?php echo $registered_user; ?>!</strong></p>
            <p>Your information has been successfully saved.</p>
        </div>

        <div class="button-group">
            <a href="index.php"><button class="btn-secondary">Submit Another Ticket</button></a>
            <a href="ticket_view.php"><button class="btn-primary">View My Tickets</button></a>
        </div>
    </div>
</body>
</html>