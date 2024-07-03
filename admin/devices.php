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
$userCode = '';


// Fetch user code

$sql = "SELECT user_code FROM users";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $userCode = $user['user_code'];
} else {
    $message = "Error fetching user code: " . $conn->error;
}


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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="devices.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <div class="sidebar">
        <script>
    document.addEventListener('DOMContentLoaded', function() {
            const message = "<?php echo isset($_SESSION['message']) ? $_SESSION['message'] : ''; ?>";
            if (message) {
                alert(message);
                <?php unset($_SESSION['message']); // Clear the message after displaying it ?>
            }
            </script>
        <div class="logo">
            <img src="" alt="ccc">
        </div>
        <br>
        <hr>
        <br>
        <div class="profile">
            <img src="" alt="">
            <p>Super Admin</p>

        </div>
        <br>
        <hr>
        <ul>
            <li><a href="dashboard.php" class="hover"><i class='bx bxs-dashboard'></i>Dashboard</a></li>
            <li><a href="" class="hover"><i class='bx bx-laptop'></i>Device</a></li>
            <li><a href="doctorinfo.html" class="hover"><i class='bx bx-plus-medical'></i>Doctor</a></li>
            <li><a href="addpatient.html" class="hover"><i class='bx bxs-user'></i>Patient</a></li>
            <li><a href="admin_manage.php" class="hover"><i class='bx bxs-calendar'></i>Doctor Schedule</a></li>
            <li><a href="patappinfo.html" class="hover"><i class='bx bx-calendar-check'></i>Patient Appointment</a></li>
            <li><a href="admin.php" class="hover"><i class='bx bxs-paste'></i>Patient case studies</a></li>
            <li><a href="" class="hover"><i class='bx bxs-capsule'></i>Prescription</a></li>
        </ul>
        <div class="logout">
            <a href="#"><i class='bx bx-exit'></i>Logout</a>
        </div>

    </div>
    <div class="content">
        <div class="topnavbar">
            <div class="gotoweb">
                <ul>
                    <li><a href=""><i class='bx bx-globe'></i>Go to website</a></li>
                </ul>
            </div>
            <div class="nav2">
                <ul>
                    <li><a href=""><i class='bx bx-conversation'></i>Chat with us</a></li>
                    <li><a href=""><i class='bx bx-health'></i>HealthEase</a></li>
                    <li><a href=""><i class='bx bxs-user-circle'></i>Mr Patient</a></li>
                </ul>
            </div>

        </div>
        <div class="cont">
            
            <h2 class="devices">Devices</h2>
            <table >
                <tr>
                    <th>ID</th>
                    <th>User ID</th>
                    <th>Device Name</th>
                    <th>Device Type</th>
                    <th>Created At</th>
                </tr>
                <?php foreach ($devices as $device) { ?>
                <tr>
                    <td>
                        <?php echo $device['id']; ?>
                    </td>
                    <td>
                    <?php echo $userCode; ?>
                    </td>
                    <td>
                        <?php echo $device['device_name']; ?>
                    </td>
                    <td>
                          <?php echo $device['device_type']; ?>
                    </td>
                    <td>
                        <?php echo $device['created_at']; ?>
                    </td>
                </tr>
                <?php } ?>
            </table>
        </div>