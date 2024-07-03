<?php
include('config.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['userid'];
    $request_text = $_POST['request_text'];

    $sql = "INSERT INTO requests (user_id, request_text, status) VALUES ('$user_id', '$request_text', 'pending')";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "Request submitted successfully!";
    } else {
        $_SESSION['message'] = "Error submitting request: " . $conn->error;
    }

    $conn->close();
    header('Location: message.php');
    exit();
}
?>
