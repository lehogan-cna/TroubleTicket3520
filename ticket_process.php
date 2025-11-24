<?php


$connect = new mysqli("localhost",
                      "tt_admin",
                      "tt",
                      "troubleticket");

                      // Check connection
if ($connect->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";

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
    return rand(1000, 9999);
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
$tid = generateUserId();
$user->addAttribute('id', $tid);
$user->addAttribute('registered', date('Y-m-d H:i:s'));

// Add user data from session
$user->addChild('first_name', $_SESSION['form_data']['first_name']); //User.name
$user->addChild('last_name', $_SESSION['form_data']['last_name']); //User.name
$user->addChild('date_submitted', $_SESSION['form_data']['date_submitted']); //Tickets.dateCreated
$user->addChild('email', $_SESSION['form_data']['email']); //User.email
$user->addChild('user_type', $_SESSION['form_data']['user_type']); //User.type
$user->addChild('title', $_SESSION['form_data']['title']); //Tickets.title
$user->addChild('ticket_info', $_SESSION['form_data']['ticket_info']); //Tickets.description
$user->addChild('priority', $_SESSION['form_data']['priority']); //Tickets.priority
$user->addChild('status', $_SESSION['form_data']['status']); //Tickets.status

// MySQL - Find if user w/ email in database
$user_query = "SELECT uid FROM User WHERE email = ?";
$email = $_SESSION['form_data']['email'];
$stmt = $connect->prepare($user_query);
$stmt->bind_param("s", $_SESSION['form_data']['email']);
$stmt->execute();
$result = $stmt->get_result();
if ($result == false) echo "No rows";
$row = $result->fetch_assoc();
if (is_null($row)) {
    // User not in database, so let's create them
    $uid = rand(1000, 9999);
    $name = $_SESSION['form_data']['first_name'];
    $stmt = $connect->prepare("INSERT INTO User (uid, name, type, email) VALUES (?,?,'STA',?)");
    $stmt->bind_param("iss", $uid, $name, $email) ;
    $stmt->execute();
} else
    $uid = $row['uid'];

$stmt = $connect->prepare("INSERT INTO Ticket (tid, title, description, uid) VALUES (?,?,?,?)");
$stmt->bind_param("isss", $tid, $_SESSION['form_data']['title'], $_SESSION['form_data']['ticket_info'], $uid) ;
$stmt->execute();

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