<?php
include('config.php');
session_start();

$message = '';
$email = '';
$password = '';
$username = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['email'])) {
        $email = $_POST['email'];
    }
    if (isset($_POST['password'])) {
        $password = $_POST['password'];
    }
    if (isset($_POST['username'])) {
        $username = $_POST['username'];
    }


    if (!empty($email) && !empty($password)) {
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result) {
            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    $_SESSION['userid'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = 'user';

                    // Get user agent string
                    $userAgent = $_SERVER['HTTP_USER_AGENT'];

                    // Parse user agent string to get device information
                    $deviceType = "Unknown";
                    if (strpos($userAgent, 'Mobile') !== false) {
                        $deviceType = "Mobile";
                    } elseif (strpos($userAgent, 'Tablet') !== false) {
                        $deviceType = "Tablet";
                    } else {
                        $deviceType = "Desktop";
                    }

                    // Store device information
                    $stmt = $conn->prepare("INSERT INTO devices (user_id, device_name, device_type) VALUES (?, ?, ?)");
                    $stmt->bind_param("iss", $user['id'], $userAgent, $deviceType);
                    $stmt->execute();

                    header('Location: user.php');
                    exit();
                } else {
                    $message = "Invalid password.";
                }
            } else {
                $message = "No user found with this email.";
            }
        } else {
            $message = "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        $message = "Email and Password cannot be empty.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="signin.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

</head>
<body>
    <form method="post" action="login.php">
        <h2> Login To Your Account</h2>
        <?php if ($message) { echo "<script>alert('$message');</script>"; } ?>
        <br><br>
        <input type="email" name="email" placeholder="email">
        <br><br>
        <input type="password" name="password" placeholder="Password">
        <br><br>
        <div class="check">
            <input type="checkbox" name="" id="">
            <br>
            <br>
            <p>Remember Me </p>
        </div>
        <br>
        <div class="sign"><button type="submit">SIGN IN</button></div>
        <br>
        <div class="line">
            <div class="line1"></div>
            <p> or </p>
            <div class="line1"></div>
        </div>
        
        <br>
        <div class="dot"><button><a href="#"> <i class='bx bxl-google'></i>  Sign in with Google</a></div></button>
        <br>
        <p>Dont have an account? <a href="register.php">Sign up</a></p>

    </form>
    
</body>
</html>
