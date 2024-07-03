<?php
include('config.php');
session_start();

function generateUniqueUserCode($conn) {
    $isUnique = false;
    while (!$isUnique) {
        $userCode = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        $sql = "SELECT id FROM users WHERE user_code = '$userCode'";
        $result = $conn->query($sql);
        if ($result->num_rows == 0) {
            $isUnique = true;
        }
    }
    return $userCode;
}

$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $userCode = generateUniqueUserCode($conn);

    $sql = "INSERT INTO users (username, email, password, user_code) VALUES ('$username', '$email', '$password', '$userCode')";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['userid'] = $conn->insert_id;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = 'user';
        $message = "Registration successful!";
        echo "<script>alert('$message'); window.location.href='login.php';</script>";
        exit();
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up</title>
    <link rel="stylesheet" href="signup.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <br>
    <br>
    <?php if ($message) { echo "<script>alert('$message');</script>"; } ?>
    <form action="register.php" method="post">
        <h2>CREATE AN ACCOUNT</h2>
        <div class="cv"><p>Create an account to enjoy all our features ad-free </p></div>      
        <br>      
        <div class="ph">
        <input type="email" name="email" id="email" placeholder="Email" required>
        <br>
        <br>
        
        <input type="text" name="username" id="username" placeholder="Username" required>
        <br>
        <br>
        
        <input type="password" name="password" id="password" placeholder="Password" required>
        <br>
        <br>
        
        <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>
    </div>
        <br>
        <br>
        <div class="sign"> <button type="submit">Register</button></div>
        
        <br>
        <div class="line">
            <div class="line1"></div>
            <p> or </p>
            <div class="line1"></div>
        </div> 
        
        <div class="dot"><button><a href="#"> <i class='bx bxl-google'></i>  Sign in with Google</a></div></button>
        <br>
        <p>Already have an account? <a href="login.php"> Sign in</a></p>
    </form>
</body>

</html>
