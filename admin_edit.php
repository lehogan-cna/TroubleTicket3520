<?php

$id = $_GET['id'];
$info = null;

$connect = new mysqli("localhost", "tt_admin","tt","troubleticket");

// Check connection
if ($connect->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// MYSQL
$user_query = "SELECT * FROM Ticket WHERE tid = ?";
$stmt = $connect->prepare($user_query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$info = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = $_POST['title'];
    $description = $_POST['description'];
    $priority = $_POST['priority'];
    $status = substr($_POST['status'], 0, 3);
    $assignedTo = empty($_POST['assignedTo']) ? '' : $_POST['assignedTo'];
    $user_query = "UPDATE Ticket SET title = ?, description = ?, priority = ?, status = ?, eid_t = ? WHERE tid = ?";
    $stmt = $connect->prepare($user_query);
    $stmt->bind_param("ssisii", $title, $description, $priority, $status, $assignedTo, $id);
    $stmt->execute();

    header('Location: admin_view.php'); // Redirect back
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN - Edit Tickets</title>
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
        <label>Title: <input type="text" name="title" value="<?php echo $info['title']; ?>"></label><br>
        <label>Description: <textarea name="description"><?php echo $info['description']; ?></textarea></label><br>
        <label>Priority:
            <select name="priority">
                <option value="high" <?php if($info['priority']==2) echo 'selected'; ?>>High</option>
                <option value="med" <?php if($info['priority']==1) echo 'selected'; ?>>Medium</option>
                <option value="low" <?php if($info['priority']==0) echo 'selected'; ?>>Low</option>
            </select>
        </label><br>
        <label> Assigned to: <input type="text" name="assignedTo" value="<?php echo $info['eid_t']; ?>"></label><br>
        <label>Status:
            <select name="status">
                <option value="open" <?php if($info['status'] =='ope') echo 'selected'; ?>>Open</option>
                <option value="closed" <?php if($info['status'] == 'clo') echo 'selected'; ?>>Closed</option>
                <option value="assigned" <?php if($info['status'] == 'ass') echo 'selected'; ?>>Assigned</option>
            </select>
        </label><br>
        <button type="submit">Save Changes</button>
    </form>
    </body>
</html>