<?php
session_start();

$conn = mysqli_connect("localhost","root","","blogspacedb");
if (mysqli_connect_error()) {
	die("connection error occured!");
}

// select commentsdb.title, COUNT(DISTINCT commentsdb.user), COUNT( DISTINCT likesdb.user) from commentsdb inner join likesdb on likesdb.title = commentsdb.title WHERE commentsdb.author='natsu dragneel' GROUP by commentsdb.title

// $sql = "SELECT bloglist.title, bloglist.author,count(commentsdb.user) as c_user FROM bloglist left join commentsdb on commentsdb.title = bloglist.title where bloglist.author=? GROUP by bloglist.title order by COUNT(commentsdb.user) desc ";

$sql = "select bloglist.title, bloglist.date, count(commentsdb.user) as c_user from bloglist left join commentsdb on commentsdb.title = bloglist.title where bloglist.author = ? GROUP by bloglist.title order by count(commentsdb.user) desc";
$stm = $conn->prepare($sql);
$stm->bind_param("s",$_SESSION['logged_user_name']);
$stm->execute();
$result = $stm->get_result();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Stats:<?php echo $_SESSION['logged_user_name']; ?></title>
	<meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="cstyle.css">
    <style type="text/css">
    .report-box{
    	background-color: gray;
    	color: white;
    	padding-top: 15px;
    	padding-left: 10px;
    	height: 55px;
    	margin-top: 5px;
    	border-radius: 5px;
    	font-size: 20px;
     }
    	
    </style>
</head>
<body>
	<?php include('templates/navBar.html'); ?>
	<div class="container">
		<h2 align="center">Statistics & Analysis</h2>
		<p></p>
		<h3>No. of comments per blog:</h3>
		<?php
		echo "<table cellspacing='10' border='1'>";
		echo "<tr><th align='center'>title</th><th>post date</th><th>no of comments</th></tr>";
		while ($row = $result->fetch_assoc()) { ?>
			<tr>
				<td><?php echo $row['title']; ?></td>
				<td><?php echo $row['date']; ?></td>
				<td align="center"><?php echo $row['c_user']; ?></td>
			</tr>
		<?php		
			
		}
		echo "</table>";

		$like_sql = "SELECT bloglist.title,count(likesdb.user) as c_user FROM bloglist left join likesdb on likesdb.title = bloglist.title where bloglist.author=? GROUP by bloglist.title order by COUNT(likesdb.user) desc";
		$stmt = $conn->prepare($like_sql);
		$stmt->bind_param("s",$_SESSION['logged_user_name']);
		$stmt->execute();
		$res = $stmt->get_result();

		echo "<h3 style='margin-top:30px;'>No. of likes per blog:</h3>";
		echo "<table border='1'>";
		echo "<tr><th>title</th><th>no of likes</th></tr>";
		while ($row = $res->fetch_assoc()) {   ?>
			<tr>
				<td><?php echo $row['title']; ?></td>
				<td align="center"><?php echo $row['c_user']; ?></td>
			</tr>
		<?php
			
		}
		echo "</table>";
		$auth_name = $_SESSION['logged_user_name'];
		$rep_sql = "select * from report where author=?";
		$rep_stm = $conn->prepare($rep_sql);
		$rep_stm->bind_param("s",$auth_name);
		$rep_stm->execute();
		$rep_result = $rep_stm->get_result();
		$rep_row = mysqli_num_rows($rep_result);
		if($rep_row>0){
			echo "<hr>";
			echo "<center><span style='color:red;'><h2>NOTICE</h2></span></center>";
			while($reprow = $rep_result->fetch_assoc()){ ?>
				<div class="report-box">
					Your blog titled  '<?php echo "<a href='".$reprow['url']."' style='color:yellow;'>".$reprow['title']."</a>"; ?>' has been reported for ' <span style="color:orange;"><?php echo $reprow['reason']; ?></span> '.
				</div>
		<?php		
			}
		}
		?>		
	</div>
	<div style="height:50px;"></div>
</body>
</html>