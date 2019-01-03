<?php
session_start();
$conn = mysqli_connect("localhost","root","","blogspacedb");
if(mysqli_connect_error()){
	die("connection error occured");
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>search result for: <?php echo $_GET['search_item']; ?></title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="cstyle.css">
    <style type="text/css">
    	tr{
    		height:100px;
    		margin-bottom: 20px;
    	}
    	img{
    		height: 100px;
    		width: 100px;
    		border-radius: 50%;
    		border: 1px solid black;
    	}
    </style>
</head>
<body>
	<?php include('templates/navBar.html'); ?>
	<div class="container">
		<div class="row" style="margin-top: 30px;">			
			<div class="col-sm-8">
				<?php 	include("templates/searchBar.php"); ?>
				<?php
				if($_SESSION['parent']=='home'){

				$title = "%".$_GET['search_item']."%";
				$sql = "select * from bloglist where title like ? ";
				$stm = $conn->prepare($sql);
				$stm->bind_param("s",$title);
				$stm->execute();

				$result = $stm->get_result();
				if ($result->num_rows == 0) {
					echo "
					<center><h3>no blogs found!</h3><p>please try with better keywords</p></center>";
				}else{

					while ($row = $result->fetch_assoc()) {  ?>
						<div class="blog_box" style="width: 100%;">
    			          <h3><a href="<?php echo $row['url']; ?>" ><?php echo $row['title']; ?></a></h3>
    			          <p><?php echo strip_tags($row['body']); ?></p>
    		            </div>
					<?php						
					}

				}
			}

			if ($_SESSION['parent']=='profile') {
				echo "<hr style='width:150%';>";
				$profile = "%".$_GET['search_item']."%";
				$sql = "select * from userdetails where user_name like ? ";
				$stm = $conn->prepare($sql);
				$stm->bind_param("s",$profile);
				$stm->execute();

				$result =  $stm->get_result();
				if ($result->num_rows == 0) {
					echo "<center><h3>no such users found!</h3></center>";
				}else{
					echo "<table cellspacing='30'>";
					while ($row = $result->fetch_assoc()) {  ?>
						<tr>
							<td><img src="<?php echo $row['profile_img']; ?>"></td>
							<td style="width: 30px;"></td>
							<td><a href="<?php echo $row['profile_url']; ?>"><h3><?php echo $row['user_name']; ?></h3></a></td>
							<td><hr style="width: 100%;"> </td>
						</tr>
				<?php
				echo "<tr style='height:30px;'></tr>";					
					}
					echo "</table>";
				}
				$stm->close();				
			}

			?>				
			</div>

			<div class="col-sm-3 col-sm-offset-1">
				
			</div>
			
		</div>
		
	</div>

</body>
</html>