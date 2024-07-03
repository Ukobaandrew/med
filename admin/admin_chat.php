<?php
include 'session_management.php';
require_admin();

if (!isset($_GET['user_id'])) {
    echo "Select a user to chat with.";
    exit;
}
$user_id = intval($_GET['user_id']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Chat</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        #chat-box {
            width: 300px;
            height: 400px;
            border: 1px solid #ccc;
            overflow-y: scroll;
            margin-bottom: 10px;
        }
        #message-form {
            display: flex;
        }
        #message-form input {
            flex: 1;
            padding: 10px;
        }
        #message-form button {
            padding: 10px;
        }
    </style>
    <script>
        function fetchMessages() {
            $.ajax({
                url: 'fetch_messages.php',
                method: 'GET',
                data: { user_id: <?php echo $user_id; ?> },
                success: function(response) {
                    try {
                        var messages = JSON.parse(response);
                        var chatBox = $('#chat-box');
                        chatBox.html('');
                        for (var i = 0; i < messages.length; i++) {
                            var message = messages[i];
                            var sender = message.sender_id == <?php echo $user_id; ?> ? 'User' : 'You';
                            chatBox.append('<div><strong>' + sender + ':</strong> ' + message.message + ' <small>(' + message.timestamp + ')</small></div>');
                        }
                        chatBox.scrollTop(chatBox[0].scrollHeight);
                    } catch (e) {
                        console.error('Error parsing messages:', e);
                    }
                },
                error: function() {
                    console.error('Error fetching messages');
                }
            });
        }

        $(document).ready(function() {
            fetchMessages();
            setInterval(fetchMessages, 5000);

            $('#message-form').submit(function(event) {
                event.preventDefault();
                var message = $('#message').val();
                $.ajax({
                    url: 'send_message.php',
                    method: 'POST',
                    data: {
                        message: message,
                        receiver_id: <?php echo $user_id; ?>
                    },
                    success: function(response) {
                        try {
                            var result = JSON.parse(response);
                            if (result.status === 'success') {
                                $('#message').val('');
                                fetchMessages();
                            } else {
                                alert('Error sending message: ' + result.message);
                            }
                        } catch (e) {
                            console.error('Error parsing response:', e);
                        }
                    },
                    error: function() {
                        alert('Failed to send message');
                    }
                });
            });
        });
    </script>
</head>
<body>
    <h1>Chat with User <?php echo $user_id; ?></h1>
    <div id="chat-box"></div>
    <form id="message-form">
        <input type="text" id="message" placeholder="Type your message..." required>
        <button type="submit">Send</button>
    </form>
</body>
</html>
