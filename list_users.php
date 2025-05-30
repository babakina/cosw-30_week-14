<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="tstyles.css">
<title>List Users</title>
</head>
<body>
<header>
	<h1>Week 14 Homework</h1>
	<nav>
        <a href="add_user.php">Add User</a>
        <a href="list_users.php">List Users</a>
	</nav>
</header>
<h2>Users</h2> 
<div>
<?php 

require 'dbconnection.php';

// SQL query to fetch all users including profile image
$sql = "SELECT user_id, first_name, last_name, email, profile_image FROM users_tbl";
$result = mysqli_query($connection, $sql);

// Check if there are results
if ($result && mysqli_num_rows($result) > 0) {
    echo "<table><tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Profile Image</th></tr>";

    while($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row["user_id"] . "</td>";
        echo "<td>" . $row["first_name"] . "</td>";
        echo "<td>" . $row["last_name"] . "</td>";
        echo "<td>" . $row["email"] . "</td>";

        // Display profile image if exists
        if (!empty($row["profile_image"])) {
            echo "<td><img src='uploads/" . $row["profile_image"] . "' alt='Profile Image' style='width:60px;height:auto;'></td>";
        } else {
            echo "<td>No image</td>";
        }

        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "0 results";
}

?>
</div>
</body>
</html>
