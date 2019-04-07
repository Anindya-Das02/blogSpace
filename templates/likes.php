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

if (isset($_POST["report_submit_btn"])) {
	$pos = strpos($url,"blog.php?");
    $substr = substr($url,$pos);
    //echo $substr; //blog.php?blog_title=Fairy+Tail+Dragon+Cry%21&author=natsu+dragneel
	$report_sql = "insert into report values (?,?,?,?,?,?)";
	$repstm = $conn->prepare($report_sql);
	$repstm->bind_param("ssssss",$title,$name,$_SESSION['logged_user_name'],$substr,$_POST['report'],$_POST['comment_reason']);
	$repstm->execute();
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
<script type="text/javascript">
	$(document).ready(function(){
		$("#report_toggle_btn").click(function(){
			$("#report_bar").toggle();
		});
	});
</script>
<div style="margin-top: 10px;">
	<hr>
	<form method="post">
		<button type="submit" name="like_button" class="like_btn" title="like" ><span class="<?php if($row_count == 0){ echo 'glyphicon glyphicon-heart-empty';}else{ echo 'glyphicon glyphicon-heart'; } ?>" style="height: 20px;"></span></button>		
	</form>
	<p style="float:right;margin-top: -40px;"> <span id="report_toggle_btn"><button class="btn btn-link" style="color: black;">report</button>
	</span> | &nbsp <i>this article was posted on: <?php echo $row['date']; ?></i></p>	
</div>
<div id="report_bar" style="display:none;margin-top: 10px;">
	<h4><u><b>Reproting this blog for:</b></u></h4>
	<form method="POST">
		<input type="radio" name="report" value="inappropriate content"> inappropriate content<br>
		<input type="radio" name="report" value="hatefull content"> hatefull content<br>
		<input type="radio" name="report" value="misleading content"> misleading content<br>
		<input type="radio" name="report" value="spam content"> spam content<br>
		<input type="radio" name="report" value="other reason"> other<br>
		<textarea rows="5" cols="30" name="comment_reason" placeholder="if other please mention the reason..."></textarea><br/>
		<input type="reset" class="btn" /> &nbsp 
		<input type="submit" name="report_submit_btn" value="Report" class="btn btn-danger"/>
	</form>
</div>