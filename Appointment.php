<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="appointments.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <section id="sidebar">
        <div class="nav">
            <div class="logo">
                <a href="#" class="brand"><i class='bx bx-user'></i>Health <span>Care</span></a>
            </div>
    
            <ul class="side-menu">
                <li><a href="dashboard.php"><i class='bx bxl-stack-overflow' ></i>overview</a></li>
                <li><a href="calendar.html"><i class='bx bx-calendar'></i>Calendar</a></li>
                <li><a href="message.php"><i class='bx bxs-chat' ></i>Message</a></li>
                <li><a href="reports.php"><i class='bx bxs-report' ></i>Reports</a></li>
                <li><a href="appointment.php" class="active"><i class='bx bxs-user' ></i>Appointments</a></li>
                <li><a href="user.html"><i class='bx bxs-user' ></i>User</a></li>
                <li><a href="logout.php"><i class='bx bx-log-out' ></i>Logout</a></li>
            </ul>
        </div>
        
        <div class="help">
            <div class="helppics">
                <img src="profile.png" alt="">
            </div>
            
            <h5>Help Center</h5>
            <br>
            <p><a href="">Having trouble?</a></p>
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
            <h1 class="title">Appointments</h1>
            <ul class="link">
                <li><a href="#">Home</a></li>
                <li class="divider">/</li>
                <li><a href="#" class="active">Appointments</a></li>
            </ul>
            <div class="main-content">
                <div class="content1">
                    <h2>Upcoming Appointments</h2>
                    <table class="content-table">
                        <thead>
                            <tr>
                                <th>Doctor</th>
                                <th>Specification</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="sum"><img src="./medium-shot-nurse-posing-with-stethoscope (1).jpg" alt="" class="img">James Carter</td>
                                <td>Cardiologist</td>
                                <td>21/1/23</td>
                                <td>45:00</td>
                                <td>Active</td>
                            </tr>
                            <!-- More rows as needed -->
                        </tbody>
                    </table>
                </div>
                <div class="content2">
                    <h2>Past Appointments</h2>
                    <table class="content-table">
                        <thead>
                            <tr>
                                <th>Doctor</th>
                                <th>Specification</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="sum"><img src="./doctor1.jpeg" alt="" class="img">Linda Brown</td>
                                <td>Dermatologist</td>
                                <td>15/1/23</td>
                                <td>30:00</td>
                                <td>Completed</td>
                            </tr>
                            <!-- More rows as needed -->
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </section>
    <script src="script.js"></script>
</body>
</html>
