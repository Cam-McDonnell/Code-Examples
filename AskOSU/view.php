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

  <!-- Main component for a primary marketing message or call to action -->
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
    	<div class="panel-heading"><h3>Thread:</h3></div>
    	<div class="panel-body">

      <?php
      include 'connect.php';

	  //Thread row
		$sql = "SELECT threadID, title, dateopened, categoryID, threadcreatorID, subject FROM `mcdoncam-db`.`Threads`";
		$result = $conn->query($sql);
		//Message row1
		$sql1 = "SELECT commenterID, comment, dateposted, threadID FROM `mcdoncam-db`.`Comments`";
		$result1 = $conn->query($sql1);
		//User row2
		$sql2 = "SELECT userID, username, datejoined FROM `mcdoncam-db`.`Users`";
		$result2 = $conn->query($sql2);
	 
	if ($result->num_rows > 0 ) {
		$counter = 1;
		$threadidArray;
		$threadtitleArray;
		$threadcreatoridArray;
		$threadcontentArray;
		$threadcategoryArray;
		$threaddatecreatedArray;
		while($row = $result->fetch_assoc()){
			
			 $threadidArray[$counter] =  $row["threadID"];
			 $threadtitleArray[$counter] = $row["title"];
			 $threadcreatoridArray[$counter] = $row["threadcreatorID"];
			 $threadcontentArray[$counter] = $row["subject"];
			 $threadcategoryArray[$counter] = $row["categoryID"];
			 $threaddatecreatedArray[$counter] = $row["dateopened"];
			
			 $counter++; 
	
		}

	} else {
           echo "0 results: Threads ";
      }
	if ($result1->num_rows > 0 ) {
		$counter = 1;
		$messagethreadidArray;
		$messageuseridArray;
		$messagecontentArray;
		$messagedatecreatedArray;
		while($row1 = $result1->fetch_assoc()){
			

			 $messagethreadidArray[$counter] = $row1["threadID"];
			 $messageuseridArray[$counter] = $row1["commenterID"];
			 $messagecontentArray[$counter] = $row1["comment"];

			 $messagedatecreatedArray[$counter] = $row1["dateposted"];
			
			 $counter++; 
	
		}

	} else {
           echo "0 results: Messages ";
      }
	if ($result2->num_rows > 0 ) {
		$counter = 1;
		$useridArray;
		$userusernameArray;
		$userjoindateArray;
		while($row2 = $result2->fetch_assoc()){
			
			 $useridArray[$counter] =  $row2["userID"];
			 $userusernameArray[$counter] = $row2["username"];
			 $userjoindateArray[$counter] = $row2["datejoined"];

			
			 $counter++; 
	
		}

	} else {
           echo "0 results: Users ";
      }  
	  
			$i = $_GET["threadid"];
				echo "<div class='table-responsive'><table class='table table-bordered'>";
				echo "<thead><tr><th>#</th><th>User</th><th>". $threadtitleArray[$i]. "</th><th>Category</th><th>Date Created</th></tr></thead>";
				echo "<tbody><tr>";
				echo "<td>". $i. "</td>";
				echo "<td>". $userusernameArray[$threadcreatoridArray[$i]]. "<br>";
				//echo "</br> <p align=center><img width='100' height='100' src='pictures/".$userimglinkArray[$threadcreatoridArray[$i]]."' alt='Profile Pic'></p><br>";				
				echo "</br> Joined:" . $userjoindateArray[$threadcreatoridArray[$i]]. "</td>";
				echo "<td>". $threadcontentArray[$i]. "</td>";
				echo "<td>". $threadcategoryArray[$i]. "</td>";
				echo "<td>". $threaddatecreatedArray[$i]. "</td>";
				echo "</tr></tbody>";
				echo "</table></div>";
				echo "<br></br>";

			for($x = 0; $x <= count($messageidArray); $x++ ){
				if($messagethreadidArray[$x] == $i){
					echo "<table class='table table-bordered'>";
					echo "<thead><tr><th>Reply</th><th>User</th><th></th><th>Date Modified</th><th>Date Created</th></tr></thead>";
					echo "<tbody><tr>";
					echo "<td></td>";
					echo "<td>" . $userusernameArray[$messageuseridArray[$x]] . "<br>";
					//echo "</br> <p align=center><img width='100' height='100' src='pictures/".$userimglinkArray[$threadcreatoridArray[$i]]."' alt='Profile Pic'></p><br>";				

					echo "</td>";
					echo "<td>" . $messagecontentArray[$x] . "</td>";
					echo "<td>" . $messagedatemodifiedArray[$x] . "</td>";
					echo "<td>" . $messagedatecreatedArray[$x] . "</td>";
					echo "</tr></tbody>";
					echo"</table>";
					echo "<br></br>";
				}
			}
      ?> 
      <br><br>
      <?php 
	  $tid = $_GET["threadid"];
	  $_SESSION["threadid"] = $tid;
      if(!empty($_SESSION["username"])){
      	echo "<a href=newmessage.html?threadid=". $tid ." role='button' class='btn btn-sm btn-success pull-left'>Reply Message</a>";
      }
      ?>
      <a href="index.php" role="button" class="btn btn-sm btn-danger pull-right">Return to main page</a>
      </div>
  </div>



    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    
  </body>
  
</html>
