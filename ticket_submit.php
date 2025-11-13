<?php
session_start();

// Redirect to first page if session not started
if (!isset($_SESSION['form_data']['first_name'])) {
    header('Location: index.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = array();

    if (empty($_POST['title'])) {
        $errors[] = "Title is required";
    } else {
        $_SESSION['form_data']['title'] = htmlspecialchars(trim($_POST['title']));
    }

    if (empty($_POST['ticket_info'])) {
        $errors[] = "Description is required";
    } else {
        $_SESSION['form_data']['ticket_info'] = htmlspecialchars(trim($_POST['ticket_info']));
    }

    if (empty($_POST['priority'])) {
        $_SESSION['form_data']['priority'] = "low";
    } else {
        $_SESSION['form_data']['priority'] = htmlspecialchars(trim($_POST['priority']));
    }

    $_SESSION['form_data']['status'] = "open";
    $_SESSION['form_data']['date_submitted'] = htmlspecialchars(trim(date('m/d/Y h:i:s a', time())));

    if (empty($errors)) {
        header('Location: ticket_process.php'); 
        exit;
    } else {
        $_SESSION['errors'] = $errors;
    }
}

// Get saved values if returning to this page
// NEED TO SET THESE UP FOR ALL VARIABLES USED BELOW
$title = isset($_SESSION['form_data']['title']) ? $_SESSION['form_data']['title'] : '';
$ticket_info = isset($_SESSION['form_data']['ticket_info']) ? $_SESSION['form_data']['ticket_info'] : '';
$priority = isset($_SESSION['form_data']['priority']) ? $_SESSION['form_data']['priority'] : '';
$status = isset($_SESSION['form_data']['priority']) ? $_SESSION['form_data']['priority'] : "open";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STAFF - Ticket Submission</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Ticket Submission - Ticket Information</h2>
        
            <a href="ticket_view.php"><button type="button" class="btn-center">View My Current Tickets</button></a>
            <br />

        <?php
        if (isset($_SESSION['errors'])) {
            echo '<div class="error">';
            foreach ($_SESSION['errors'] as $error) {
                echo '<p>' . $error . '</p>';
            }
            echo '</div>';
            unset($_SESSION['errors']);
        }
        ?>

        <form method="POST" action="">

            <div class="form-group">
                <label for="title">Ticket Title (short) <span>*</span></label>
                <input type="text" id="title" name="title" value="<?php echo $title; ?>" required>
            </div>

            <div class="form-group">
                <label for="ticket_info">Ticket Description <span>*</span></label>
                <input type="text" id="ticket_info" name="ticket_info" 
                       value="<?php echo $ticket_info; ?>" required>
            </div>

            <div class="form-group">
                <label for="priority">User Type <span>*</span></label>
                <select id="priority" name="priority" required>
                    <option value="high" <?php echo $priority == 'high' ? 'selected' : ''; ?>>High</option>
                    <option value="medium" <?php echo $priority == 'medium' ? 'selected' : ''; ?>>Medium</option>
                    <option value="low" <?php echo $priority == 'low' ? 'selected' : ''; ?>>Low</option>
                </select>
            </div>

            <div class="button-group">
                <a href="index.php"><button type="button" class="btn-secondary">← Back</button></a>
                <!--a href="ticket_view.php"><button type="button" class="btn-secondary">View My Current Tickets</button></a-->
                <button type="submit" class="btn-primary">Submit New Ticket →</button>
            </div>
        </form>
    </div>
</body>
</html>