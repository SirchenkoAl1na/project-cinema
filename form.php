<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        form {
            max-width: 400px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        input[type="phone"],input[type="text"], input[type="submit"], button {
            width: 90%;
            padding: 10px;
            margin-top: 10px;
        }
        input[type="submit"], button {
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover, button:hover {
            background-color: #218838;
        }
    </style>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sending SMS</title>
</head>
<body>
    <form action="./send.php" method="post">
        <h2>Send SMS</h2>
        <input type="phone" name="phone" placeholder="Enter phone number" required>
        <!-- text -->
        <input type="text" name="text" placeholder="Enter message text" required>

        <button role="submit">Send</button>
    </form>
</body>
</html>