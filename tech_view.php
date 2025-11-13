<?php
session_start();

$xml_file = 'data/tickets.xml';

// Check if XML file exists
$tickets_exist = file_exists($xml_file);

if ($tickets_exist) {
    $xml = simplexml_load_file($xml_file);
    $tickets = $xml->ticket;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TECH - View My Tickets</title>
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
        <h2>Tickets Assigned</h2>

        <?php if (!$tickets_exist || count($tickets) == 0): ?>
            <div class="error">
                <p>No tickets registered yet.</p>
            </div>
        <?php else: ?>
            
            <table id="ticketsTable">
                <thead>
                    <tr>
                        <th>Ticket ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Date Submitted</th>
                        <th>User Type</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <!-- Add Column for Assigned? -->
                         <th>Edit?</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tickets as $ticket): ?>
                        
                        <tr>
                            <?php if ($ticket->email == $_SESSION['form_data']['email']): ?>
                                <td><?php echo $ticket['id']; ?></td>
                                <td><?php echo $ticket->first_name . ' ' . $ticket->last_name; ?></td>
                                <td><?php echo $ticket->email; ?></td>
                                <td><?php echo $ticket->date_submitted; ?></td>
                                <td><?php echo ucfirst($ticket->user_type); ?></td>
                                <td><?php echo $ticket->title; ?></td>
                                <td><?php echo $ticket->ticket_info; ?></td>
                                <td><?php echo $ticket->priority; ?></td>
                                <!-- TODO: Add entry for assigned -->
                                <td><?php echo $ticket->status; ?></td>
                                <td><a href="admin_edit.php?id=<?php echo $ticket['id'] ?>"> Edit </a></td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <div class="button-group" style="margin-top: 30px;">
            <a href="index.php"><button class="btn-primary">Submit New Ticket</button></a>
        </div>
    </div>

    <script>
        function filterTable() {
            const searchInput = document.getElementById('searchInput').value.toLowerCase();
            const priorityFilter = document.getElementById('priorityFilter').value.toLowerCase();
            const table = document.getElementById('ticketsTable');
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) {
                const row = rows[i];
                const name = row.cells[1].textContent.toLowerCase();
                const email = row.cells[2].textContent.toLowerCase();
                const priority = row.cells[7].textContent.toLowerCase();

                const matchesSearch = name.includes(searchInput) || email.includes(searchInput);
                const matchesPriority = priorityFilter === '' || priority.includes(priorityFilter);

                if (matchesSearch && matchesPriority) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        }
    </script>
</body>
</html>