<?php
include('config.php');
include('encryption.php');
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
    <title>Doctors Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="evo-calendar.min.css">
    <link rel="stylesheet" href="evo-calendar.midnight-blue.min.css">
</head>
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

<body>
    <section id="sidebar" class="sidebar">
        <div class="nav">
            <div class="logo">
                <a href="#" class="brand">Health <span>Care</span></a>
            </div>

            <ul class="side-menu">
                <li><a href="dashboard.php" class="active"><i class='bx bxl-stack-overflow'></i>overview</a></li>
                <li><a href="calendar.html"><i class='bx bx-calendar'></i>Calendar</a></li>
                <li><a href="message.php"><i class='bx bxs-chat'></i>Request</a></li>
                <li><a href="reports.php"><i class='bx bxs-report'></i>Reports</a></li>
                <li><a href="Appointment.php"><i class='bx bxs-user'></i>Appointment</a></li>
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
        <main>
            <h1 class="title">Dashboard</h1>
            <ul class="link">
                <li><a href="#">Home</a></li>
                <li class="divider">/</li>
                <li><a href="#" class="active">Dashboard</a></li>
            </ul>
            <div class="content">
                <div class="top">
                    <section class="greetings">
                        <h2 class="Patient">Welcome Dr. <?php echo $_SESSION['username']; ?></h2>
                        <p class="checking">How are you feeling today?</p>
                    </section>
                </div>
                <div class="main-content">
                    <div class="content1">
                        <div class="intro">
                            <div class="reference">
                                <div class="card1">
                                    <div class="head1">
                                        <div>
                                            <h2>Find the best doctors with<br> Health Care</h2>
                                            <p>Appoint the doctors and get finest medical services</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <img src="./doctor.webp" alt="" srcset="" class="dock">
                        </div>
                        <div class="info">
                            <h3 class="vitals">Vitals</h3>
                            <div class="info-data">
                                <div class="card">
                                    <div class="head">
                                        <div>
                                            <h4>Body Temperature</h4>
                                            <p>36.2 <span>*c</span></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="head">
                                        <div>
                                            <h4>pulse</h4>
                                            <p>85 <span>bpm</span></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="head">
                                        <div>
                                            <h4>Blood Pressure</h4>
                                            <p>80/70 <span>mm/kg</span></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="head">
                                        <div>
                                            <h4>Breathing Rate</h4>
                                            <p>15 <span>breaths/m</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="appointment-info">
                                <h3 class="Appointments">Requests</h3>
                                
                                <?php if (isset($message)) { echo "<script>alert('$message');</script>"; } ?>
    <p>Your Unique ID: <?php echo $userCode; ?></p>
    <table class="content-table" >
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
                            </div>
                        </div>
                    </div>
                    <div class="content2">
                        <section class="reports">
                            <table class="content-table">

                                <thead>
                                    <tr>
                                        <th>My Reports</th>
                                        <th></th>

                                    </tr>
                                </thead>

                                <tbody>
                                    <tr>
                                        <td><a href="#" class="active"><i class='bx bxl-stack-overflow'></i>Glucose</a>
                                        </td>
                                        <td>02/1/2023</td>
                                    </tr>
                                    <tr>
                                        <td><a href="#" class="active"><i class='bx bxl-stack-overflow'></i>Glucose</a>
                                        </td>
                                        <td>02/1/2023</td>
                                    </tr>
                                    <tr>
                                        <td><a href="#" class="active"><i class='bx bxl-stack-overflow'></i>Glucose</a>
                                        </td>
                                        <td>02/1/2023</td>
                                    </tr>
                                    <tr>
                                        <td><a href="#" class="active"><i class='bx bxl-stack-overflow'></i>Glucose</a>
                                        </td>
                                        <td>02/1/2023</td>
                                    </tr>
                                    <tr>
                                        <td><a href="#" class="active"><i class='bx bxl-stack-overflow'></i>Glucose</a>
                                        </td>
                                        <td>02/1/2023</td>
                                    </tr>
                                </tbody>
                            </table>
                        </section>
                        <section class="calendar">
                            <div class="hero">
                                <div id="calendar"></div>
                            </div>
                            <script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js"></script>
                            <script src="evo-calendar.min.js"></script>
                            <script>
                                $(document).ready(function () {
                                    $('#calendar').evoCalendar({
                                        calendarEvents: [
                                            {
                                                id: 'bHay68s', // Event's ID (required)
                                                name: "New Year", // Event name (required)
                                                date: "January/1/2020", // Event date (required)
                                                description: "New Year's Day", // Event description (optional)
                                                type: "holiday", // Event type (required)
                                                everyYear: true // Same event every year (optional)
                                            },
                                            {
                                                name: "Vacation Leave",
                                                badge: "02/13 - 02/15", // Event badge (optional)
                                                date: ["February/13/2020", "February/15/2020"], // Date range
                                                description: "Vacation leave for 3 days.", // Event description (optional)
                                                type: "event",
                                                color: "blueviolet" // Event custom color (optional)
                                            },
                                            {
                                                id: "defaultEvent",
                                                name: "Default Event",
                                                date: "February/15/2020" // Same date as the other event
                                            }
                                        ]
                                    })
                                });
                            </script>
                        </section>
                    </div>
                </div>
        </main>
    </section>
</body>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const toggleButton = document.querySelector('.toggle-sidebar');
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('content');
        const mainContent = document.querySelector('.content2');
        const dock = document.querySelector('.dock');
        const Calendar = document.querySelector('#calendar');
        toggleButton.addEventListener('click', function () {
            sidebar.classList.toggle('active');
            content.classList.toggle('active');
            mainContent.classList.toggle('expanded-padding');
            Calendar.classList.toggle('padding');
            dock.classList.toggle('dot');
        });
    });
</script>

</html>