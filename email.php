<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="fstyles.css">
    <title>Email Form</title>
</head>
<body>
    <h1>Contact Us!</h1>
    <main>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!empty($_POST['name']) && !empty($_POST['comments'])) {
            // Create email body
            $name = $_POST['name'];
            $email = $_POST['email'];
            $comments = $_POST['comments'];

            $body = "<p>Name: $name</p><p>Email: $email</p><p>Comments: $comments</p>";
            $body = wordwrap($body, 70);

            // Set headers
            $headers  = "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=UTF-8\r\n";
            $headers .= "From: <no-reply@webdevlearning.org>\r\n";
            $headers .= "Bcc: <0836731@lbcc.edu>\r\n";

            // Send email
            mail('class@webdevlearning.org', 'Contact Form Submission', $body, $headers);

            echo '<p class="italic">Thank you for contacting us! We will reply someday.</p>';

            // Clear form fields
            $_POST = [];
        } else {
            echo '<p class="form-error">Please fill out the form completely.</p>';
        }
    }
    ?>

    <p>To contact us, fill out this form:</p>
    <form action="email.php" method="post">
        <p><span class="bold">Name:</span> 
            <input type="text" name="name" size="30" maxlength="60" 
            value="<?php if (isset($_POST['name'])) echo $_POST['name']; ?>">
        </p>
        <p><span class="bold">Email Address:</span> 
            <input type="email" name="email" size="30" maxlength="80" 
            value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>">
        </p>
        <p><span class="bold">Comments:</span><br>
            <textarea name="comments" rows="5" cols="30"><?php 
                if (isset($_POST['comments'])) echo $_POST['comments']; 
            ?></textarea>
        </p>
        <p><input type="submit" name="submit" id="submit" class="clay" value="Send!"></p>
    </form>
    </main>
</body>
</html>
