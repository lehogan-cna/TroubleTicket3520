<?php
$xml_file = 'data/tickets.xml';
$xml = simplexml_load_file($xml_file);

$id = $_GET['id'];
$ticket = null;

foreach ($xml->ticket as $t) {
    if ((string)$t['id'] === $id) {
        $ticket = $t;
        break;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ticket->title = $_POST['title'];
    $ticket->description = $_POST['description'];
    $ticket->priority = $_POST['priority'];
    $ticket->status = $_POST['status'];
    $ticket->assignedTo = $_POST['assignedTo'];

    $xml->asXML($xml_file); // Save changes back to XML
    header('Location: tech_view.php'); // Redirect back
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TECH - Edit Tickets</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .container {
            max-width: 1200px;
        }
        .filter-group {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .filter-group input,
        .filter-group select {
            flex: 1;
        }
    </style>
</head>
<body>
    <div class="container">

    <form method="post">
        <label>Title: <input type="text" name="title" value="<?php echo $ticket->title; ?>"></label><br>
        <label>Description: <textarea name="description"><?php echo $ticket->description; ?></textarea></label><br>
        <label>Priority:
            <select name="priority">
                <option value="high" <?php if($ticket->priority=='high') echo 'selected'; ?>>High</option>
                <option value="med" <?php if($ticket->priority=='med') echo 'selected'; ?>>Medium</option>
                <option value="low" <?php if($ticket->priority=='low') echo 'selected'; ?>>Low</option>
            </select>
        </label><br>
        <label> Assigned to: <input type="text" name="assignedTo" value="<?php echo $ticket->assignedTo; ?>"></label><br>
        <label>Status:
            <select name="status">
                <option value="open" <?php if($ticket->status=='open') echo 'selected'; ?>>Open</option>
                <option value="closed" <?php if($ticket->status=='closed') echo 'selected'; ?>>Closed</option>
                <option value="assigned" <?php if($ticket->status=='assigned') echo 'selected'; ?>>Assigned</option>
            </select>
        </label><br>
        <button type="submit">Save Changes</button>
    </form>
    </body>
</html>