<?php
include('config.php');
session_start();

if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
    exit();
}

$userid = $_SESSION['userid'];
$requests = [];
$userCode = '';

// Fetch user code
$sql = "SELECT user_code FROM users WHERE id = '$userid'";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $userCode = $user['user_code'];
} else {
    $message = "Error fetching user code: " . $conn->error;
}

// Fetch user requests
$sql = "SELECT * FROM requests WHERE user_id = '$userid'";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $requests[] = $row;
    }
} else {
    $message = "Error fetching requests: " . $conn->error;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request</title>
    <link rel="stylesheet" href="message.css">
    <link rel="stylesheet" href="dashboard.css">
</head>

<body>
    <section id="sidebar">
        <div class="nav">
            <div class="logo">
                <a href="#" class="brand">Health <span>Care</span></a>
            </div>

            <ul class="side-menu">
                <li><a href="dashboard.php" class="active"><i class='bx bxl-stack-overflow'></i>overview</a></li>
                <li><a href="calendar.html"><i class='bx bx-calendar'></i>Calendar</a></li>
                <li><a href="message.php"><i class='bx bxs-chat'></i>Message</a></li>
                <li><a href="reports.php"><i class='bx bxs-report'></i>Reports</a></li>
                <li><a href="user.php"><i class='bx bxs-user'></i>User</a></li>
                <li><a href="logout.php"><i class='bx bx-log-out'></i>Logout</a> </li>
            </ul>
        </div>

        <div class="help">
            <div class="helppics">
                <img src="profile.png" alt="">
            </div>

            <h5>Help Center</h5>
            <br>
            <p><a href="">Having trouble?</a> </p>
        </div>
    </section>
    <section id="content">
        <nav>
            <i class='bx bx-menu toggle-sidebar'></i>
            <form action="#">
                <div class="form-group">
                    <input type="text" placeholder="Search..." id="search">
                    <i class='bx bx-search icon'></i>
                </div>

                <div class="nav-right">
                    <a href="#" class="nav-link">
                        <i class='bx bxs-bell'></i>
                        <span class="badge">5</span>
                    </a>
                    <a href="#" class="nav-link">
                        <i class="bx bxs-message-square-dots"></i>
                        <span class="badge">8</span>
                    </a>
                </div>
            </form>
        </nav>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const message = "<?php echo isset($_SESSION['message']) ? $_SESSION['message'] : ''; ?>";
            if (message) {
                alert(message);
                <?php unset($_SESSION['message']);// Clear the message after displaying it ?>
            }
        });
    </script>
        <main>
            <h1 class="title">Make A Request</h1>
            <?php if (isset($message)) { echo "<script>alert('$message');</script>"; } ?>
            <div class="reference">
                <!-- <div class="card1"> -->
                <h2 class="meds">Medical Request</h2>
                <form action="submit_request.php" method="post">
                    <div class="inputname">
                        <div class="inputfirstname">
                            <label for="name" class="name">First Name</label>

                            <input type="text" placeholder="FirstName" name="firstname" id="firstname" required>
                        </div>
                        <div class="inputlastname">
                            <label for="name" class="name">Last Name</label>
                            <input type="text" placeholder="LastName" name="lastname" id="lastname" required>
                        </div>
                    </div>
                    <div class="mail">
                        <div class="inputemailname">
                            <label for="name" class="name">Email</label>
                            <input type="email" placeholder="Email" name="email" id="email" required>
                        </div>
                        <div class="inputnumber">
                            <label for="name" class="name">Phone Number</label>
                            <input type="tel" name="number" id="num" placeholder="### ### ####" required>
                        </div>
                    </div>
                    <div class="more">

                       
                        <div class="lad">
                            <label for="medical">Medical Notes</label>
                            <textarea name="request_text" id="request_text" placeholder="Enter Request"></textarea>
                        </div>
                    </div>

                    <input type="submit" value="Submit" class="submit">
                </form>
                <div class="your-request">
                    <h2 class="Request-made">Your Request</h2>
                    <ul class="items">
    <?php if (isset($message)) { echo "<script>alert('$message');</script>"; } ?>
    <p>Your Unique ID: <?php echo $userCode; ?></p>
    <table class="message-table" >
        <thead>
        <tr>
        
            <th>ID</th>
            <th>Details</th>
            <th>Status</th>
            <th>Created At</th>
        </tr>
        </thead>
        <?php if (!empty($requests)) {
            foreach ($requests as $request) { ?>
            <tbody>
            <tr>
                <td><?php echo $request['id']; ?></td>
                <td><?php echo $request['request_text']; ?></td>
                <td><?php echo $request['status']; ?></td>
                <td><?php echo $request['created_at']; ?></td>
            </tr>
            </tbody>
        <?php }
        } else { ?>
            <tr>
                <td colspan="4">No requests found.</td>
            </tr>
        <?php } ?>
    </table>
    </ul>  </div>
            </div>
            </div>
            </div>
        </main>
        <script src="script.js"></script>
</body>

</html>