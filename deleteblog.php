<?php
session_start();
$name = $_GET['name'];
$conn = mysqli_connect("localhost","root","","blogspacedb");
if (mysqli_connect_error()) {
	die("connection error occured!");
}

if ($_SESSION['logged_user_name'] != $name) {
    die("unauthorized entry!");
}

$sql = "select * from bloglist where author='$name' order by date desc";
$ress = $conn->query($sql);
// $row = $ress->fetch_assoc();

?>

<!DOCTYPE html>
<html>
<head>
	<title>Delete blog</title>
	<meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="cstyle.css">
    <style type="text/css">
    	a{
    		margin-left: 5px;
    	}
    	.del_show{
    		background-color: lightgray;
    		margin: 10px;
    		height: 53px;
    		padding: 10px;
    		overflow: hidden;
    		border-radius: 5px;
    	}
    	.del_show:hover{
    		box-shadow: 0px 3px 3px 0px;
    	}
    </style>
</head>
<body>

	<?php include('templates/navBar.html'); ?>
	<div class="container">
		<h1 align="center" >DELETING BLOGS:</h1>
		<h4 align="center" style="color:red;">CAUTION! you are about to delete your blogs. any deleted item cannot be restored back! proceed with caution!</h4>

		<div style="margin-top: 30px;">
			<?php

			if (mysqli_num_rows($ress)==0) {
				echo "no blogs!";
				die();
			}
			echo "<form method='post'>";
			while($row = $ress->fetch_assoc()){  ?>
				<div class="del_show">
				<input type="radio" name="title" value="<?php echo $row['title']; ?>" ><a href="<?php echo $row['url']; ?>"><?php echo $row['title']; ?></a>: <?php echo strip_tags($row['body']); ?> <br/>
			    </div>
				<?php 				
			}
			echo "<p></p><center>";
			echo "<input type='submit' class='btn btn-danger' name='del_submit' value='confirm delete' />&nbsp";
			echo "<input type='reset' name='reset' class='btn btn-primary' value='cancel' /> ";
			echo "</center></form>";
			?>		

		</div>
	</div>

</body>
</html>

<?php

if (isset($_POST['del_submit'])) {

	$title = strip_tags($_POST['title']);
	//echo $title;
	
	$del_sql = "delete from bloglist where author='$name' and title='$title'";
	$res = $conn->query($del_sql);
	if($res == true){
		echo "<script>alert('deleted!please refresh the page!');</script>";
	}
	else{
		echo "<script>alert('oops! an error occured!');</script>";
	}
}


?>