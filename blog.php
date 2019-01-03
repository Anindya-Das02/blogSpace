<?php
session_start();

$suthor_name = $_SESSION['logged_user_name'];
$name = $_GET['author'];
$title = $_GET['blog_title'];

$conn = mysqli_connect("localhost","root","","blogspacedb");
if(mysqli_connect_error()){
	die("connection error occured!");
}

$sql = "select * from bloglist where author=? and title=? ";
$stm = $conn->prepare($sql);
$stm->bind_param("ss",$name,$title);
$stm->execute();

$result = $stm->get_result();
if ($result->num_rows == 0) {
	die("no such blog found!");
}

$row = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html>
<head>
	<title><?php echo $row['title']; ?></title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="cstyle.css">
</head>
<body>
	<?php include('templates/navBar.html'); ?>
	<?php

	$auth_name = $_GET['author'];
	$sql_join = "select bloglist.author , userdetails.profile_url from bloglist INNER join userdetails on bloglist.author = userdetails.user_name where bloglist.author = '$auth_name'";
	$ress = $conn->query($sql_join);
	$rc = mysqli_num_rows($ress);
	$t_row = $ress->fetch_assoc();


	?>
	<div class="container">
		<h1 align="center"><?php echo $row['title']; ?></h1>
		<center><i>by</i><a href="<?php echo $t_row['profile_url']; ?>"> <?php echo $row['author']; ?></a></center>

	    <div class="view_body">
	    	<?php echo $row['body']; ?>
	    </div>

	    <?php include('templates/likes.php'); ?>

	    <?php include('comments.php'); ?>
		

	</div>
	<?php include('templates/footer.html');  ?>


</body>
</html>