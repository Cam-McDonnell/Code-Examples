<?php
session_start();
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
            <?php 
            if(!empty($_SESSION["username"])){
            ?>
            <ul class="nav navbar-nav navbar-right">
              <li><a href="profile.php"><i class="fa fa-user"></i>&nbsp Hello,
                <b><?php session_start(); echo $_SESSION["username"];?></b></a></li>
              <li><a href="logout.php">Logout</a></li>
            </ul>
            </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>
            <?php
            } else {
            ?>
            <ul class="nav navbar-nav navbar-right">
              <li><a href="register.html">Register</a></li>
              <li><a href="login.html">Login</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav> <?php } ?>
  <form role="search" action="searchnon.php" method="post">
      <div class="input-group">
        <input type="text" class="form-control" placeholder="Search Title Here..." id="search" name="search">
        <span class="input-group-btn">
          <button class="btn btn-info" type="submit"><i class="glyphicon glyphicon-search"></i></button>
        </span>
      </div>
    </form>

  <br>
	<div class="panel panel-default">
    <div class="panel-heading">
      <?php
      include 'connect.php';
      if (!empty($_POST['submit'])) {
        $searchtag = $_POST['category'];
        echo '<h3>Category: <b>' . $searchtag . "</b></h3>";
      }
      ?></div>
    <div class="panel-body">
	<?php
	$input = "SELECT threadID, title, categoryID, dateopened FROM `mcdoncam-db`.`Threads` WHERE categoryID = '$searchtag'";
	$query = mysqli_query($conn, $input) or die(mysqli_error($conn));
  $count = mysqli_num_rows($query);
  if ($count != 0) {
  	echo "<div class='table-responsive'><table class='table table-bordered'>";
  	echo "<thead><tr><th>Title</th><th>Category</th><th>Date</th></tr></thead>";
  	while ($result = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
      echo "<tbody><tr>";
  		echo "<td align='justify'><a href=view.php?threadid=".$result["id"].">". $result["title"] . "</a></td>";
  		echo "<td align='justify'>". $result["category"] . "</td>";
  		echo "<td align='justify'>". $result["datecreated"] . "</td>";
  		echo "</tr></tbody>";
  	}
  	echo "</table></div>";
  	echo "<br></br>";
  } else {
    echo "No results found.";
  }
	mysqli_close($conn);
	?>
  </div>
</div>
</body>
</html>