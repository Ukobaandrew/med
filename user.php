<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="userprofile.css">
    <link rel="stylesheet" href="dashboard.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

</head>
<body>
    <section id="sidebar">
        <div class="nav">
            <div class="logo">
                <a href="#" class="brand">Health <span>Care</span></a>
            </div>
    
            <ul class="side-menu">
                <li><a href="dashboard.php" class="active"><i class='bx bxl-stack-overflow' ></i>overview</a></li>
                <li><a href="calendar.html"><i class='bx bx-calendar'></i>Calendar</a></li>
                <li><a href="message.php"><i class='bx bxs-chat' ></i>Message</a></li>
                <li><a href="reports.php"><i class='bx bxs-report' ></i>Reports</a></li>
                <li><a href="user.php"><i class='bx bxs-user' ></i>User</a></li>
                <li><a href="logout.php"><i class='bx bx-log-out' ></i>Logout</a> </li>
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
                    <input type="text" placeholder="Search...">
                    <i class='bx bx-search icon' ></i>
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
    <section id="content1">
        <div class="top">
            <h2>Profile</h2>
            <button><i class='bx bx-key'></i> Password Setting</button>
        </div>

        <div class="container">
            <div class="head">
                <img src="profile.png" alt="img">
                <h3>Super Admin</h3>
            </div>
            <div class="ct">
                <h3>Name</h3>
                <p>Super Admin</p>
            </div>
            <div class="ct1">
                <h3>Username</h3>
                <p> Admin</p>
            </div>
            <div class="ct2">
                <h3>Email</h3>
                <p>pros12am@gmail.com</p>
            </div>
        </div>
            
          

            
        </form>

        <div class="display">
            <h2>Profile information</h2>
            
                <form action="" method="post">
                    <div class="bdy">
                        <div class="input">
                            <input type="file" id="myFile" name="Filename" >
                            <img id="imageDisplay" src="" alt="upload image">
                        </div>
                        
                        <div class="sec">
                            <div class="right">
                                <h4>Name</h4>
                                <input type="text" name="Name" id="name" placeholder="Name">
                                <h4>Email</h4>
                                <input type="email" name="email" id="email" placeholder="Email">
                            </div>
                        
                        </div>
                    
                        
                    </div>
                    <div class="btn">
                        <button>Save Changes</button>
                    </div>
                    
                </form>
               
            </div>
        </div>
       

    </section>

    <script>
        document.getElementById('myFile').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imgElement = document.getElementById('imageDisplay');
                    imgElement.src = e.target.result;
                    imgElement.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>