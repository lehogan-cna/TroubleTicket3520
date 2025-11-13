<?php
session_start();

// Initialize session variables if not set
if (!isset($_SESSION['form_data'])) {
    $_SESSION['form_data'] = array();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate inputs
    $errors = array();

    if (empty($_POST['first_name'])) {
        $errors[] = "First name is required";
    } else {
        $_SESSION['form_data']['first_name'] = htmlspecialchars(trim($_POST['first_name']));
    }

    if (empty($_POST['last_name'])) {
        $errors[] = "Last name is required";
    } else {
        $_SESSION['form_data']['last_name'] = htmlspecialchars(trim($_POST['last_name']));
    }

    /*if (empty($_POST['date_submitted'])) {
        $errors[] = "Date is required";
    } else {
        $_SESSION['form_data']['date_submitted'] = htmlspecialchars(trim($_POST['date_submitted']));
    }*/

    if (empty($_POST['user_type'])) {
        $_SESSION['form_data']['user_type'] = "";
    } else {
        $_SESSION['form_data']['user_type'] = htmlspecialchars(trim($_POST['user_type']));
    }

    if (empty($_POST['email'])) {
        $errors[] = "Email address is required";
    } else {
        $_SESSION['form_data']['email'] = htmlspecialchars(trim($_POST['email']));
    }

    // If no errors, proceed to next page
    if (empty($errors)) {
        if ($_SESSION['form_data']['user_type'] == 'staff') {
            header('Location: ticket_submit.php');
        } else if ($_SESSION['form_data']['user_type'] == 'tech') {
            header('Location: tech_view.php');
        } else {
            header('Location: admin_view.php');
        }
        exit;
    } else {
        $_SESSION['errors'] = $errors;
    }
}

// Get saved values if returning to this page
// NEED TO SET THESE UP FOR ALL VARIABLES USED BELOW
$first_name = isset($_SESSION['form_data']['first_name']) ? $_SESSION['form_data']['first_name'] : '';
$last_name = isset($_SESSION['form_data']['last_name']) ? $_SESSION['form_data']['last_name'] : '';
$date_submitted = isset($_SESSION['form_data']['date_submitted']) ? $_SESSION['form_data']['date_submitted'] : '';
//$email = isset($_SESSION['form_data']['email']) ? $_SESSION['form_data']['email'] : '';
$user_type = isset($_SESSION['form_data']['user_type']) ? $_SESSION['form_data']['user_type'] : '';
$status = isset($_SESSION['form_data']['status']) ? $_SESSION['form_data']['status'] : "open";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ALL - Ticket Submission System Entry</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Ticket Submission System - User Information</h2>

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
                <label for="email">Email Address<span>*</span></label>
                <input type="email" id="email" name="email" 
                       value="<?php echo $email; ?>" required>
            </div>

            <div class="form-group">
                <label for="first_name">First Name <span>*</span></label>
                <input type="text" id="first_name" name="first_name" 
                       value="<?php echo $first_name; ?>" required>
            </div>

            <div class="form-group">
                <label for="last_name">Last Name <span>*</span></label>
                <input type="text" id="last_name" name="last_name" 
                       value="<?php echo $last_name; ?>" required>
            </div>

            <div class="form-group">
                <label for="user_type">User Type <span>*</span></label>
                <select id="user_type" name="user_type" required>
                    <option value="staff" <?php echo $user_type == 'staff' ? 'selected' : ''; ?>>Staff (Enter tickets)</option>
                    <option value="tech" <?php echo $user_type == 'tech' ? 'selected' : ''; ?>>Technician (Update tickets)</option>
                    <option value="admin" <?php echo $user_type == 'admin' ? 'selected' : ''; ?>>Admin (Assign tickets)</option>
                </select>
            </div>

            <div class="button-group">
                <button type="submit" class="btn-primary">Next →</button>
            </div>
        </form>
    </div>
</body>
</html>