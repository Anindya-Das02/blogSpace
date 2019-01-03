<?php
session_start();
$_SESSION['parent'] = 'fresh';

$conn = mysqli_connect("localhost","root","","blogspacedb");
if (mysqli_connect_error()) {
	die("connection error occured!");
}

$sql = "select * from bloglist order by date DESC, time DESC";
$result = $conn->query($sql);
$rowcount = mysqli_num_rows($result);


?>

<!DOCTYPE html>
<html>
<head>
	<title>Home page</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="cstyle.css">
</head>
<body>

	<?php include('templates/navBar.html'); ?>

	<div class="container">

		<div class="row" style="margin-top: 30px;">
			<div class="col-sm-8">
				<!-- <?php include('templates/searchBar.php'); ?> -->
				<?php

				if($rowcount == 0){
					echo "no blogs yet!";
				}else{
					while ($row = $result->fetch_assoc()) {  ?>
						<div class="blog_box" style="width: 100%;">
    			          <h3><a href="<?php echo $row['url']; ?>" ><?php echo $row['title']; ?></a></h3>
    			          <p><?php echo strip_tags($row['body']); ?></p>
    		            </div>
					
					<?php


					}


				}



				?>


				
			</div>
			<div class="col-sm-offset-1 col-sm-3">
				
			</div>
		</div>
	</div>



</body>
</html>