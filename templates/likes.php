<?php
// $conn = mysqli_connect("localhost","root","","blogspacedb");
// if (mysqli_connect_error()) {
// 	die("connection error occured");
// }
$user = $_SESSION['logged_user_name'];
$like_sql = "select * from likesdb where author = ? and title = ? and user = ? ";
$stmt = $conn->prepare($like_sql);
$stmt->bind_param("sss",$name,$title,$user);
$stmt->execute();
$res_like = $stmt->get_result();

$url = $_SERVER['REQUEST_URI'];

$row_count = $res_like->fetch_assoc();

if (isset($_POST['like_button'])) {
	if($row_count == 0){
		$sql_ins = "insert into likesdb (title,author,user) values (?,?,?) ";
		$stmt2 = $conn->prepare($sql_ins);
		$stmt2->bind_param("sss",$title,$name,$user);
		$stmt2->execute();
		header("Location: ".$url);
	}
	elseif ($row_count > 0) {
		$rem_like_sql ="delete from likesdb where title=? and author=? and user=? ";
		$stmt3 = $conn->prepare($rem_like_sql);
		$stmt3->bind_param("sss",$title,$name,$user);
		$stmt3->execute();
		header("Location: ".$url);
	}
}

?>
<style type="text/css">
	.like_btn{
		background-color: transparent;
		border: 0px;		
	}
	.glyphicon.glyphicon-heart-empty {
    font-size: 30px;
    }
    .glyphicon.glyphicon-heart{
    	font-size: 30px;
    }
</style>
<div style="margin-top: 10px;">
	<hr>
	<form method="post">
		<button type="submit" name="like_button" class="like_btn" title="like" ><span class="<?php if($row_count == 0){ echo 'glyphicon glyphicon-heart-empty';}else{ echo 'glyphicon glyphicon-heart'; } ?>" style="height: 20px;"></span></button>		
	</form>
	<p style="float:right;margin-top: -40px;"><i>this article was posted on: <?php echo $row['date']; ?></i></p>
	
</div>