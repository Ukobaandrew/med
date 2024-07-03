<?php
include('../config.php');
include('../encryption.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT id, username, password, role FROM admins WHERE username = '$username' AND role = 'admin'";
    $result = $conn->query($sql);
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['userid'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        
        header('Location: dashboard.php');
    } else {
        echo "Invalid admin credentials";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Login Page</title>
  <link rel="stylesheet" href="admin.css
  " />
</head>

<body>
  <div class="container1">
    <img class="door" src="./waiting-concept-illustration_114360-5821.jpg" />
  </div>

  <div class="container2">
    <h1 class="heading">Log In</h1>
    <p class="text">
      Log in with your data that you entered during
      <br />
      registration
    </p>

    <form action="admin_login.php" method="post">
      <div class="form">
        <label class="original" for="username">Username</label>
        <input type="text" name="username" id="username" placeholder="Username" />

        <label class="original" for="password">Password</label>
        <input type="text" name="password" id="password" placeholder="Password" />
      </div>
      <div class="info">
        <p class="info2">
          <input class="tickbox" type="checkbox" /> Keep me logged in
        </p>
        <p class="forget">Forgot Password ?</p>
      </div>
      <div class="box">
        <button type="submit" class="login">Login</button>
      </div>
    </form>
  </div>
</body>

</html>
