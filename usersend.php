<?php
include('config.php');
session_start();

if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
    exit();
}

// Fetch user-specific data (e.g., requests) if needed
$user_id = $_SESSION['userid'];
$sql = "SELECT * FROM requests WHERE user_id = '$user_id'";
$result = $conn->query($sql);
$requests = [];
while ($row = $result->fetch_assoc()) {
    $requests[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const message = "<?php echo isset($_SESSION['message']) ? $_SESSION['message'] : ''; ?>";
            if (message) {
                alert(message);
                <?php unset($_SESSION['message']);// Clear the message after displaying it ?>
            }
        });
    </script>
</head>
<body>
    <h1>Welcome, <?php echo $_SESSION['username']; ?></h1>

    <h2>Submit a Request</h2>
    <form action="submit_request.php" method="post">
        <label for="request_text">Request Details:</label><br>
        <textarea id="request_text" name="request_text" required></textarea><br>
        <button type="submit">Submit Request</button>
    </form>

    <h2>Your Requests</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Details</th>
            <th>Status</th>
            <th>Created At</th>
        </tr>
        <?php foreach ($requests as $request) { ?>
        <tr>
            <td><?php echo $request['id']; ?></td>
            <td><?php echo $request['request_text']; ?></td>
            <td><?php echo ucfirst($request['status']); ?></td>
            <td><?php echo $request['created_at']; ?></td>
        </tr>
        <?php } ?>
    </table>

    <a href="logout.php">Logout</a>
</body>
</html>
