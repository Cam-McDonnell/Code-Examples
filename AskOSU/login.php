<!DOCTYPE html>
<html lang="en">
<head>

  <title>AskOregonState</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
session_start();
include 'connect.php';

$username = $_POST["username"];
$password = md5($_POST["password"]);

if (!empty($username) && !empty($password)) {
    $input = "SELECT Users.userID FROM `mcdoncam-db`.`Users` WHERE username = '$username' AND password = '$password'";
    $query = mysqli_query($conn, $input) or die(mysqli_error($conn)); 
    $count = mysqli_num_rows($query);
    $row = mysqli_fetch_array($query, MYSQLI_ASSOC);
    if ($count > 0) {
        $_SESSION["userLogin"] = $row["userID"];
        $_SESSION["username"] = $username;
        echo "<br><p align=center>Login Success! Welcome, " . $_SESSION["username"]. "!</p><br>";
        echo '<div class="form-actions"><a href="index.php" role="button" class="btn btn-lg btn-success"> Click here to proceed to main page</a></div>';
    } else {
        echo "<br><p align=center>Sorry, invalid Email OR Password. </p><br>";
        echo '<div class="form-actions"><a href="login.html" role="button" class="btn btn-lg btn-danger"> Click here to retry</a></div>';
    }
}    


mysqli_close($conn);
?>
<br>
</body>
</html>