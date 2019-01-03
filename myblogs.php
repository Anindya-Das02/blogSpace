<?php
session_start();

$author = $_GET['user'];
if ($author != $_SESSION['logged_user_name']) {
    die("unauthorized authentication!");
}

$conn = mysqli_connect("localhost","root","","blogspacedb");
if (mysqli_connect_error()) {
	die("connection error occured!");
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>my blogs list</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="cstyle.css">
</head>
<body>
	<?php include('templates/navBar.html'); ?>

	<div class="container" style="margin-top: 20px;">

	<?php

	$sql = "select * from bloglist where author='$author' order by date desc, time desc";
    $result = $conn->query($sql);
    $rowcount = mysqli_num_rows($result);
    if($rowcount == 0){
	   die("no data found!");
    }
    else{
    	echo "<div style='margin-top:20px;'>";
    	while($row = $result->fetch_assoc()){  ?>

    		<div class="blog_box">
    			<h3><a href="<?php echo $row['url']; ?>" ><?php echo $row['title']; ?></a></h3>
    			<p><?php echo strip_tags($row['body']); ?></p>
    		</div>
        <?php
    	}
    	echo "</div>";
    }

	?>
</div>

<?php include('templates/footer.html');  ?>

</body>
</html>