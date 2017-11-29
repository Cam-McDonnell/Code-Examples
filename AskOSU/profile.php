<?php
    session_start();
    if(!empty($_SESSION["username"])) {
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<meta name="description" content="">
<meta name="author" content="">
<link rel="icon" href="../../favicon.ico">

<title>AskOregonState</title>

<!-- Bootstrap  CSS and FontAwesome too -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

<!-- Custom styles for this template -->
<link href="navbar.css" rel="stylesheet">
<link href="style.css" rel="stylesheet">

</head>

<body>

<div class="container">

<!-- Static navbar -->
<nav class="navbar navbar-default ">
<div class="container-fluid">
<div class="navbar-header">
<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
<span class="sr-only">Toggle navigation</span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
</button>
<a class="navbar-brand" href="index.php">AskOregonState</a>
</div>
<div id="navbar" class="navbar-collapse collapse">
<ul class="nav navbar-nav">
<div class="navbar-form form-inline">
                <form method="post" action="category.php">
                  <select class="form-control" name="category">
                    <option selected>--CATEGORIES--</option>
                    <option value="1">Classes</option>
                    <option value="2">Events</option>
                    <option value="3">Housing</option>
                    <option value="4" name="Food">Food</option>
                    <option value="5">Directions</option>
                    <option value="6">Other</option>
                  </select>
                  <input type="submit" value="submit" name="submit"/>
                </form>
              </div>
            </ul>

<ul class="nav navbar-nav navbar-right">
<li><a href="profile.php"><i class="fa fa-user"></i>&nbsp Hello,
<b><?php session_start(); echo $_SESSION["username"];?></b></a></li>
<li><a href="logout.php"><b>Logout</b></a></li>
</ul>
</div><!--/.nav-collapse -->
</div><!--/.container-fluid -->
</nav>

<form role="search" action="searchnon.php" method="post">
      <div class="input-group">
        <input type="text" class="form-control" placeholder="Search Title Here..." id="search" name="search">
        <span class="input-group-btn">
          <button class="btn btn-info" type="submit"><i class="glyphicon glyphicon-search"></i></button>
        </span>
      </div>
    </form>

<br><br>
<div class="col-md-3 pull-left">
<ul class="nav nav-pills nav-stacked">
<li class="active"><a href="#">Home</a></li>
<li><a href="mythread.php?">My Thread</a></li>
<li><a href="changepassword.html">Change Password</a></li>
</ul>
</div>

<?php
   /* include 'connect.php';
    if(isset($_POST['submit'])){
        move_uploaded_file($_FILES['file']['tmp_name'],"pictures/".$_FILES['file']['name']);
        //use rename to solve pics conflict.
        //if(rename("/pictures/'".$_FILES['file']['name']."'", "/pictures/'".$_SESSION['username'].".png'")){echo "success";}
        //rename("/pictures/default.png", "/pictures/haha.png");
        $q = mysqli_query($conn,"UPDATE User SET imglink = '".$_FILES['file']['name']."' WHERE username = '".$_SESSION['username']."'");
    } */
    ?>


<?php
	$username = $_SESSION['username'];
    $q = $conn->prepare("SELECT * FROM Users WHERE username = ?");
	$q->bind_param("s", $username);
	$q->execute();
	$selectresult = mysqli_stmt_get_result($q);
    $row = mysqli_fetch_assoc($selectresult);
    if($row['imglink'] == ""){
        echo "<p align=center><h3>Welcome back ".$_SESSION['username']."!</h3></p>";
        echo "<br>";
        echo "<p align=center><img width='100' height='100' src='pictures/default.png' alt='Profile Pic'></p>";
    } else {
        echo "<center><p align=center>Welcome back ".$_SESSION['username']."!</p>";
        echo "<br>";
        echo "<p align=center><img width='100' height='100' src='pictures/".$row['imglink']."' alt='Profile Pic'></p>";
    }
    ?>
<!-- upload pictures, reference: pastebin.com/vJFUcvka -->
<br>
<br>
<br>

<form action="" method="post" enctype="multipart/form-data" style="text-align:left">
    <b>Change Profile Picture </b><input type="file" name="file">
    <input type="submit" name="submit">
</form>






<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

</body>

</html>
<?php 
} else {
    header("Location: login.html");
} ?>