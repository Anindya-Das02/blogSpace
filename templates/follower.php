<?php

$conn = mysqli_connect("localhost","root","","blogspacedb");
if (mysqli_connect_error()) {
	die("connection error occured");
}
$header_url = $_SERVER['PHP_SELF'];

$logged_user =  $_SESSION['logged_user_name'];
$check_sql = "select * from followerlist where follower = ? and follows = ? ";
$stmt = $conn->prepare($check_sql);
$stmt->bind_param("ss",$logged_user,$name);
$stmt->execute();
$res_foll = $stmt->get_result();

if (isset($_POST['submit_follow'])) {
	if ($res_foll->num_rows == 0) {
		
		$ins_sql = "insert into followerlist(follower,follows) values (?,?) ";
		$stmt2 = $conn->prepare($ins_sql);
		$stmt2->bind_param("ss",$logged_user,$name);
		$stmt2->execute();
		header("Location: ".$header_url."?user_name=".urlencode($name)."&user_email=".urlencode($email));

	}
	elseif ($res_foll->num_rows > 0) {
		$del_sql = "delete from followerlist where follower = ? and follows = ? ";
		$stmt3 = $conn->prepare($del_sql);
		$stmt3->bind_param("ss",$logged_user,$name);
		$stmt3->execute();
		header("Location: ".$header_url."?user_name=".urlencode($name)."&user_email=".urlencode($email));
	}
}
?>
<div style="margin-top: 40px;">
	<form method="post">
		<button type="submit" name="submit_follow" class="<?php if($res_foll->num_rows > 0){echo 'btn btn-primary';}else{
			echo 'btn btn-info';
		} ?>" > <?php if ($res_foll->num_rows == 0) {
			echo "follow";
		}else{
			echo "following";
		} ?> </button>		
	</form>	
</div>

<?php $stmt->close(); ?>

