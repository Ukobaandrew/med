<?php
include('../config.php');
session_start();

if (!isset($_SESSION['userid']) || $_SESSION['role'] != 'admin') {
    header('Location: admin_login.php');
    exit();
}

// Initialize session message
if (!isset($_SESSION['message'])) {
    $_SESSION['message'] = '';
}

// Initialize arrays

$users = [];
$devices = [];
$doctors = [];
$requests = [];
$message = '';





// Fetch monthly user registrations
$user_registration_data = [];
$sql = "SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, COUNT(*) AS count FROM users GROUP BY month";
$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $user_registration_data[] = $row;
    }
} else {
    $_SESSION['message'] = "Error fetching user registration data: " . $conn->error;
}




// Handle user verification
if (isset($_POST['verify_user'])) {
    $user_id = $_POST['user_id'];
    $sql = "UPDATE users SET verified = 1 WHERE id = '$user_id'";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "User has been verified successfully!";
    } else {
        $_SESSION['message'] = "Error verifying user: " . $conn->error;
    }
}

// Handle user deletion
if (isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];
    $sql = "DELETE FROM users WHERE id = '$user_id'";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "User has been deleted successfully!";
    } else {
        $_SESSION['message'] = "Error deleting user: " . $conn->error;
    }
}

// Handle login as user
if (isset($_POST['login_as_user'])) {
    $user_id = $_POST['user_id'];
    $sql = "SELECT * FROM users WHERE id = '$user_id'";
    $result = $conn->query($sql);
    if ($result) {
        $user = $result->fetch_assoc();
        $_SESSION['userid'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header('Location: ../dashboard.php');
        exit();
    } else {
        $_SESSION['message'] = "Error logging in as user: " . $conn->error;
    }
}

// Handle doctor status update
if (isset($_POST['update_doctor_status'])) {
    $doctor_id = $_POST['doctor_id'];
    $status = $_POST['status'];
    $sql = "UPDATE doctors SET status = '$status' WHERE id = '$doctor_id'";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "Doctor status has been updated successfully!";
    } else {
        $_SESSION['message'] = "Error updating doctor status: " . $conn->error;
    }
}

// Handle adding a new doctor
if (isset($_POST['add_doctor'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $specification = $_POST['specification'];
    $status = $_POST['status'];
    $sql = "INSERT INTO doctors (name, email, specification, status) VALUES ('$name', '$email', '$specification', '$status')";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "Doctor has been added successfully!";
    } else {
        $_SESSION['message'] = "Error adding doctor: " . $conn->error;
    }
}

// Fetch users
$sql = "SELECT * FROM users";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
} else {
    $message = "Error fetching users: " . $conn->error;
}

// Fetch devices
$sql = "SELECT * FROM devices";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $devices[] = $row;
    }
} else {
    $message = "Error fetching devices: " . $conn->error;
}

// Fetch doctors
$sql = "SELECT * FROM doctors";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $doctors[] = $row;
    }
} else {
    $message = "Error fetching doctors: " . $conn->error;
}

// Fetch requests
$sql = "SELECT requests.id, requests.request_text, requests.status, requests.created_at, users.username 
        FROM requests JOIN users ON requests.user_id = users.id";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $requests[] = $row;
    }
} else {
    $message = "Error fetching requests: " . $conn->error;
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Management</title>
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const message = "<?php echo isset($_SESSION['message']) ? $_SESSION['message'] : ''; ?>";
            if (message) {
                alert(message);
                <?php unset($_SESSION['message']); // Clear the message after displaying it ?>
            }

            const ctx = document.getElementById('userRegistrationChart').getContext('2d');
            const userRegistrationData = <?php echo json_encode($user_registration_data); ?>;

            const labels = userRegistrationData.map(data => data.month);
            const counts = userRegistrationData.map(data => data.count);

            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Number of Registered Users',
                        data: counts,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
</head>
<body>
    <h1>Admin Management</h1>

    <h2>Number of Registered Users per Month</h2>
    <canvas id="userRegistrationChart" width="400" height="200"></canvas>

    <h2>Users</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Created At</th>
            <th>Verified</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($users as $user) { ?>
        <tr>
            <td><?php echo $user['id']; ?></td>
            <td><?php echo $user['username']; ?></td>
            <td><?php echo $user['email']; ?></td>
            <td><?php echo $user['created_at']; ?></td>
            <td><?php echo $user['verified'] ? 'Yes' : 'No'; ?></td>
            <td>
                <form method="post" style="display:inline-block;">
                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                    <button type="submit" name="verify_user" <?php if ($user['verified']) echo 'disabled'; ?>>Verify</button>
                </form>
                <form method="post" style="display:inline-block;">
                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                    <button type="submit" name="delete_user">Delete</button>
                </form>
                <form method="post" style="display:inline-block;">
                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                    <button type="submit" name="login_as_user">Login as User</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>

    <h2>Devices</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Device Name</th>
            <th>Device Type</th>
            <th>Created At</th>
        </tr>
        <?php foreach ($devices as $device) { ?>
        <tr>
            <td><?php echo $device['id']; ?></td>
            <td><?php echo $device['user_id']; ?></td>
            <td><?php echo $device['device_name']; ?></td>
            <td><?php echo $device['device_type']; ?></td>
            <td><?php echo $device['created_at']; ?></td>
        </tr>
        <?php } ?>
    </table>

    <h2>Doctors</h2>
    <form method="post">
        <label for="name">Name:</label>
        <input type="text" name="name" required>
        <label for="email">Email:</label>
        <input type="email" name="email" required>
        <label for="specification">Specification:</label>
        <input type="text" name="specification" required>
        <label for="status">Status:</label>
        <select name="status">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>
        <button type="submit" name="add_doctor">Add Doctor</button>
    </form>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Specification</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($doctors as $doctor) { ?>
        <tr>
            <td><?php echo $doctor['id']; ?></td>
            <td><?php echo $doctor['name']; ?></td>
            <td><?php echo $doctor['email']; ?></td>
            <td><?php echo $doctor['specification']; ?></td>
            <td><?php echo ucfirst($doctor['status']); ?></td>
            <td>
                <form method="post" style="display:inline-block;">
                    <input type="hidden" name="doctor_id" value="<?php echo $doctor['id']; ?>">
                    <select name="status">
                        <option value="active" <?php if ($doctor['status'] == 'active') echo 'selected'; ?>>Active</option>
                        <option value="inactive" <?php if ($doctor['status'] == 'inactive') echo 'selected'; ?>>Inactive</option>
                    </select>
                    <button type="submit" name="update_doctor_status">Update Status</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>



     <h1>Admin Management</h1>
    <?php if ($message) { echo "<script>alert('$message');</script>"; } ?>
    
    <h2>Users</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Created At</th>
            <th>Verified</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($users as $user) { ?>
        <tr>
            <td><?php echo $user['id']; ?></td>
            <td><?php echo $user['username']; ?></td>
            <td><?php echo $user['email']; ?></td>
            <td><?php echo $user['created_at']; ?></td>
            <td><?php echo $user['verified']; ?></td>
            <td>
                <a href="verify_user.php?id=<?php echo $user['id']; ?>">Verify</a>
                <a href="delete_user.php?id=<?php echo $user['id']; ?>">Delete</a>
                <a href="login_as_user.php?id=<?php echo $user['id']; ?>">Login as User</a>
            </td>
        </tr>
        <?php } ?>
    </table>

    <h2>Devices</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Device Name</th>
            <th>Device Type</th>
            <th>Created At</th>
        </tr>
        <?php foreach ($devices as $device) { ?>
        <tr>
            <td><?php echo $device['id']; ?></td>
            <td><?php echo $device['user_id']; ?></td>
            <td><?php echo $device['device_name']; ?></td>
            <td><?php echo $device['device_type']; ?></td>
            <td><?php echo $device['created_at']; ?></td>
        </tr>
        <?php } ?>
    </table>

    <h2>Doctors</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Specification</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($doctors as $doctor) { ?>
        <tr>
            <td><?php echo $doctor['id']; ?></td>
            <td><?php echo $doctor['name']; ?></td>
            <td><?php echo $doctor['email']; ?></td>
            <td><?php echo $doctor['specification']; ?></td>
            <td><?php echo $doctor['status']; ?></td>
            <td>
                <a href="activate_doctor.php?id=<?php echo $doctor['id']; ?>">Activate</a>
                <a href="deactivate_doctor.php?id=<?php echo $doctor['id']; ?>">Deactivate</a>
            </td>
        </tr>
        <?php } ?>
    </table>

    <h2>Requests</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>User id</th>
            <th>Request</th>
            <th>Status</th>
            <th>Created At</th>
        </tr>
        <?php foreach ($requests as $request) { ?>
        <tr>
            <td><?php echo $request['id']; ?></td>
            <td><?php echo $request['username']; ?></td>
            <td><?php echo $request['request_text']; ?></td>
            <td><?php echo $request['status']; ?></td>
            <td><?php echo $request['created_at']; ?></td>
        </tr>
        <?php } ?>
    </table>

    <a href="admin_logout.php">Logout</a>
</body>
</html>
