<?php 
session_start();
    if(!empty($_SESSION["username"])) {

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <!--This website is inspired from the help of bootstrap template: http://getbootstrap.com/examples/signin/; -->

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
include 'connect.php';
//get the username
$username = $_SESSION["username"];
$password = md5($_POST["originalPassword"]);
$newpassword = md5($_POST["inputPassword"]);
$newcpassword = md5($_POST["inputCPassword"]);
    if ($newcpassword != $newpassword) {
        echo "<br><p align=center>You entered your new password incorrectly.</p></br>";
        echo '<div class="form-actions"><a href="profile.php" role="button" class="btn btn-lg btn-danger"> Click here to retry</a></div>';
    }
    else {
        $input = "SELECT password FROM `mcdoncam-db`.`Users` WHERE username = '$username'";
        $query = mysqli_query($conn, $input) or die(mysqli_error($conn));
        $count = mysqli_num_rows($query);
        if ($count != 0) {
          $row = mysqli_fetch_array($query, MYSQLI_ASSOC);
          $sql = "UPDATE Users SET password = '$newpassword' WHERE username = '$username'";
          if (mysqli_query($conn, $sql)) {
            echo "<br><p align=center>Password updated successfully.</p><br>";
            echo '<div class="form-actions"><a href="profile.php" role="button" class="btn btn-lg btn-success"> Click here to continue</a></div>';
          } else {
            echo "<br><p align=center>Error updating password. Please try again.</p></br>";
            echo '<div class="form-actions"><a href="changepassword.html" role="button" class="btn btn-lg btn-danger"> Click here to retry</a></div>';

          }
        }
}

//reference:http://www.w3schools.com/php/php_mysql_update.asp
    
mysqli_close($conn);
?>
<br>
</body>
</html>
<?php 
} else {
    header("Location: login.html");
} ?>