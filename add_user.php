<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Users Form</title>
  <link rel="stylesheet" href="fstyles.css">
</head>
<body>
<header>
	<h1>Week 14 Homework</h1>
	<nav>
        <a href="add_user.php">Add User</a>
        <a href="list_users.php">List Users</a>
	</nav>
</header>
<main>
  <h2>Add User</h2>
  <form action="" method="post" enctype="multipart/form-data">
    <span class="line-break">
      <label for="first-name">First Name:</label>
      <input type="text" name="first-name" id="first-name" placeholder="First Name"
             value="<?php if (isset($_POST['first-name'])) echo htmlspecialchars($_POST['first-name']); ?>">
    </span>

    <span class="line-break">
      <label for="last-name">Last Name:</label>
      <input type="text" name="last-name" id="last-name" placeholder="Last Name"
             value="<?php if (isset($_POST['last-name'])) echo htmlspecialchars($_POST['last-name']); ?>">
    </span>

    <span class="line-break">
      <label for="email">Email:</label>
      <input type="email" name="email" id="email" placeholder="Email"
             value="<?php if (isset($_POST['email'])) echo htmlspecialchars($_POST['email']); ?>">
    </span>

    <label for="password">Password:</label>
    <input type="password" name="password" id="password" placeholder="Password">
    <input type="password" name="confirm-password" id="confirm-password" placeholder="Confirm Password">

    <label for="upload">Profile Image:</label>
    <input type="hidden" name="MAX_FILE_SIZE" value="524288">
    <input type="file" name="upload" id="upload" accept="image/jpeg,image/png">

    <input type="submit" value="Submit" id="submit" class="clay">
  </form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $problem = false;

  // Basic form validation
  if (empty($_POST['first-name'])) {
    $problem = true;
    echo '<p class="error">Please enter your first name.</p>';
  }
  if (empty($_POST['last-name'])) {
    $problem = true;
    echo '<p class="error">Please enter your last name.</p>';
  }
  if (empty($_POST['email'])) {
    $problem = true;
    echo '<p class="error">Please enter your email.</p>';
  }
  if (empty($_POST['password']) || empty($_POST['confirm-password'])) {
    $problem = true;
    echo '<p class="error">Please enter and confirm your password.</p>';
  } elseif ($_POST['password'] !== $_POST['confirm-password']) {
    $problem = true;
    echo '<p class="error">Passwords do not match.</p>';
  }

  $uploadedFileName = null;

  if (isset($_FILES['upload'])) {
    $allowed = ['image/pjpeg', 'image/jpeg', 'image/JPG', 'image/X-PNG', 'image/PNG', 'image/png', 'image/x-png'];

    if (in_array($_FILES['upload']['type'], $allowed)) {
      $uploadedFileName = basename($_FILES['upload']['name']);
      $uploadPath = "uploads/" . $uploadedFileName;

      if (move_uploaded_file($_FILES['upload']['tmp_name'], $uploadPath)) {
        echo '<p><em>The image has been uploaded!</em></p>';
      } else {
        $problem = true;
        echo '<p class="error">Failed to move uploaded file.</p>';
      }
    } elseif ($_FILES['upload']['error'] !== 4) {
      $problem = true;
      echo '<p class="error">Please upload a JPEG or PNG image.</p>';
    }

    if ($_FILES['upload']['error'] > 0) {
      echo '<p class="error">File upload error: <strong>';
      switch ($_FILES['upload']['error']) {
        case 1: echo 'The file exceeds the upload_max_filesize setting in php.ini.'; break;
        case 2: echo 'The file exceeds the MAX_FILE_SIZE setting in the HTML form.'; break;
        case 3: echo 'The file was only partially uploaded.'; break;
        case 4: echo 'No file was uploaded.'; break;
        case 6: echo 'No temporary folder was available.'; break;
        case 7: echo 'Unable to write to the disk.'; break;
        case 8: echo 'File upload stopped.'; break;
        default: echo 'A system error occurred.'; break;
      }
      echo '</strong></p>';
      $problem = true;
    }

    // Clean up temporary file
    if (file_exists($_FILES['upload']['tmp_name']) && is_file($_FILES['upload']['tmp_name'])) {
      unlink($_FILES['upload']['tmp_name']);
    }
  }

  // If no problem, insert user into database
  if (!$problem) {
    require('dbconnection.php');

    $firstname = mysqli_real_escape_string($connection, $_POST['first-name']);
    $lastname = mysqli_real_escape_string($connection, $_POST['last-name']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);
    $image = $uploadedFileName ? mysqli_real_escape_string($connection, $uploadedFileName) : null;

    $sql = "INSERT INTO users_tbl (first_name, last_name, email, password, profile_image)
            VALUES ('$firstname', '$lastname', '$email', '$password', '$image')";

    if (mysqli_query($connection, $sql)) {
      echo '<p class="form-success">' . htmlspecialchars($firstname) . ' ' . htmlspecialchars($lastname) . ' added as a new user.</p>';
      $_POST = []; // Clear form
    } else {
      echo '<p class="error">Database error: ' . mysqli_error($connection) . '</p>';
    }

    mysqli_close($connection);
  } else {
    echo '<p class="error">Please fix the errors above and try again.</p>';
  }
}
?>
</main>
</body>
</html>
