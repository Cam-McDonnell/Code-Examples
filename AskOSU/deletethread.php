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

$threadname = $_POST["threadname"];
if (!empty ($_POST["threadname"])) {
  $q = mysqli_query($conn,"SELECT userID FROM Users WHERE username = '".$_SESSION['username']."'");
  $row = mysqli_fetch_assoc($q);
  $delete = "UPDATE Threads SET subject = '<b>THREAD DELETED!</b>' WHERE title = '$threadname' AND threadcreatorID=".$row['id']."";
  if (mysqli_query($conn,$delete)) {
    echo "<br><p align=center>Thread deleted successfully! " . $_SESSION["username"]. "!</p><br>";
    echo '<div class="form-actions"><a href="mythread.php" role="button" class="btn btn-lg btn-success"> Click here to proceed to continue</a></div>';
  } else {
      echo "<br><p align=center> Sorry, error deleting record: invalid thread title </p><br>";
      echo '<div class="form-actions"><a href="deletethread.html" role="button" class="btn btn-lg btn-danger"> Click here to retry</a></div>';
      }
} 
//http://www.w3schools.com/php/php_mysql_delete.asp
mysqli_close($conn);
?>
<br>
</body>
</html>
<?php 
} else {
    header("Location: login.html");
} ?>