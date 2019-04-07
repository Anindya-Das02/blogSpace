<?php
session_start();
//echo $_SESSION['logged_user_name'];

$conn = mysqli_connect("localhost","root","","blogspacedb");
if(mysqli_connect_error()){
	die("connection error occured!");
}

$old_blog_title = $_GET['blogtitle'];

$sql = "select * from bloglist where author=? and title=? ";
$stm = $conn->prepare($sql);
$stm->bind_param("ss",$_SESSION['logged_user_name'],$_GET['blogtitle']);
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
	<title>edit blog</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="cstyle.css">
    <style type="text/css">
    	.forms{
    		margin:10px;
    	}
    	.preview_box{
    	    width: 100%;
	        margin-top: 20px;
	        margin-bottom: 30px;  
	        background-color: #FFFAF0;
	        padding:10px;  		

    	}
    </style>
</head>
<body>
	<?php include('templates/navBar.html'); ?>
	<div class="container">
		<h1 align="center">Edit blog</h1>
		<form method="POST">
			<label for="blog_title"><h2>Enter Blog title:</h2></label>
		          <input type="text" class="form-control" name="blog_title" placeholder="Enter title here..." id="btitle" pattern=".{4,}" value="<?php if(isset($_POST['preview'])){ echo $_POST['blog_title']; }  else if(isset($row['title'])){ echo $row['title']; } else if(isset($_POST['reset'])){ echo $row['title']; } ?>" style="width:65%;"  required/>
		          <h2>Write body:</h2>
		          <textarea id="bbody" name="blog_body" rows="15" cols="103" style="margin-top: 10px; overflow: scroll;resize: none;" placeholder="write something here..."><?php if(isset($_POST['blog_body'])){ echo $_POST['blog_body']; } if(isset($row['body'])){ echo $row['body']; } if(isset($_POST['reset'])){ echo $row['body']; } ?></textarea><br>
		          <input type="submit" name="save_changes" class="btn btn-success" value="save changes"/>&nbsp
		          <input type="submit" name="preview" class="btn btn-info" value="preview"/>&nbsp
		          <input type="submit" name="reset" class="btn btn-warning" value="reset"/>
		</form>
	    <div style="margin-top:20px;">	
			<?php

			if (isset($_POST['preview'])) {
				require_once 'C:\WAMP\wamp64\www\blogSpace\htmlpurifier-4.10.0\htmlpurifier-4.10.0\library\HTMLPurifier.auto.php'; 
                $purifier = new HTMLPurifier(); 
                $dirty_html = $_POST["blog_body"];
                $clean_html = $purifier->purify($dirty_html);               
                echo "<div class='preview_box'>";
                //echo "<h1>$_POST['blog_title']</h1>";
                echo "<h1 align='center'>".$_POST['blog_title']."</h1>";
                echo $clean_html;
                echo "</div>";       
			}

			if(isset($_POST['save_changes'])){
				$new_blog_title = $_POST['blog_title'];
				$new_blog_body = $_POST['blog_body'];
				$new_date = date('y-m-d');
				$new_time = time();
				$new_url = "blog.php?blog_title=".urlencode($new_blog_title)."&author=".$_SESSION['logged_user_name'];
				$sql = "update bloglist set title = ? ,body = ?, url = ?, date = ? , time = ? where author = ? and title = ? ";
				$stmt = $conn->prepare($sql);
				$stmt->bind_param("ssssiss",$new_blog_title,$new_blog_body,$new_url,$new_date,$new_time,$_SESSION['logged_user_name'],$old_blog_title);
				$res = $stmt->execute();
				if($res == true){ 
					header("Location: ".$new_url);
				}else{			
				    echo "<script>alert('error occured');</script>";		
				}
				$stmt->close();

			}           
			?>
		</div>
	</div>


<?php $stm->close();$conn->close(); ?>

</body>
</html>