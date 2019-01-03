<style type="text/css">
	.comments_box{
		margin-top: 50px;
	}
	.com{
		height:auto;
		width: 60%;
		background-color: #FCF3CF;
		padding: 5px;
		margin-top: 10px;
		margin-bottom: 10px;
		border-radius: 4px;
	}
</style>
<div class="comments_box">
	<h4>Comments:</h4>
	<form method="post">
		<textarea name="comment_txt" rows="5" cols="90" placeholder="Add a comment" ></textarea><br/>
		<input type="submit" name="comm_submit" class="btn" value="comment">		
	</form>
	
</div>
<?php

$author = $_GET['author'];
$title = $_GET['blog_title'];
$user = $_SESSION['logged_user_name'];

$connect = mysqli_connect("localhost","root","","blogspacedb");
if (mysqli_connect_error()) {
	die("connection error occured!");
}

if (isset($_POST['comm_submit'])) {
	require_once 'C:\WAMP\wamp64\www\blogSpace\htmlpurifier-4.10.0\htmlpurifier-4.10.0\library\HTMLPurifier.auto.php'; 
    $purifier = new HTMLPurifier(); 
    $dirty_html = "<pre>".$_POST["comment_txt"]."</pre>";
    $clean_html = $purifier->purify($dirty_html); 

    $date = date('y-m-d');
    $time = time();

    $com_sql = "insert into commentsdb (author,title,user,comment_body,date,time) values ('$author','$title','$user','$clean_html','$date',$time)";
    $res = $connect->query($com_sql);
    if ($res == true) {
    	
    }else{
    	echo "error occured!";
    }
}

echo "<hr>";

$ch_comm = "select userdetails.user_name,userdetails.profile_url, commentsdb.comment_body , commentsdb.author,commentsdb.date from commentsdb inner join userdetails on userdetails.user_name = commentsdb.user where commentsdb.author = ? and commentsdb.title = ? ORDER by date DESC, time DESC ";
$stm = $connect->prepare($ch_comm);
$stm->bind_param("ss",$author,$title);
$stm->execute();
$result = $stm->get_result();

if ($result->num_rows ==0) {
	echo "<center><p>no comments yet</p></center>";
}else{

	while ($row = $result->fetch_assoc()) {  ?>

		<div class="com">
			<a href="<?php echo $row['profile_url']; ?>" style="color: black;padding-left:10px;" ><b><u><?php echo $row['user_name']; ?></u></b></a>&nbsp<i style="float:right;"><?php echo $row['date'];  ?></i><br/>
			<p style="padding-left:25px;background-color:#FCF3CF; ">
				<?php echo $row['comment_body']; ?>
			</p>
		</div>
	<?php 	
		
	}


}



?>