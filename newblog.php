<?php

session_start();

$author_name = $_SESSION['logged_user_name'];

$conn = mysqli_connect("localhost","root","","blogspacedb");
if (mysqli_connect_error()) {
	die("connection error occured!");
}

if (isset($_POST['upload'])) {
	require_once 'C:\WAMP\wamp64\www\blogSpace\htmlpurifier-4.10.0\htmlpurifier-4.10.0\library\HTMLPurifier.auto.php'; 
    $purifier = new HTMLPurifier(); 
    $dirty_html = $_POST["blog_body"];
    $clean_html = $purifier->purify($dirty_html); 
    date_default_timezone_set('Asia/Kolkata');
    $blog_title = $_POST['blog_title'];
	$blog_body = $clean_html;
	$time = time();

    $blog_url = "blog.php?blog_title=".urlencode($blog_title)."&author=".urlencode($author_name);
	
	$date = date('y-m-d');
	$auth_name = urlencode($author_name);
	$blog_title_url = urlencode($blog_title);

	$sql = "insert into bloglist (author,title,body,url,date,time) values (?,?,?,?,?,?)";
	$stm = $conn->prepare($sql);
	$stm->bind_param("sssssi",$author_name,$blog_title,$blog_body,$blog_url,$date,$time);

	$res = $stm->execute();
	if ($res == true) {
		// header("Location: blog.php?author=".$auth_name."&blog_title=".$blog_title_url);
		header("Location: blog.php?blog_title=".$blog_title_url."&author=".$auth_name);
	}else{
		echo "<script>alert('error occured');</script>";
	}
	$stm->close();

}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Create new blog</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="cstyle.css">
    <style type="text/css">
    	.bss{
    		background-color: lightskyblue;height: 480px;margin-top: 25px;
    		overflow: scroll;
    		border: 2px solid black;
    		list-style-type:disc;
    		list-style-position:outside;
    	}

    	.preview_box{
    	    width: 100%;
	        margin-top: 20px;
	        margin-bottom: 30px;  
	        background-color: #FFFAF0;
	        padding:10px;  		

    	}


    </style>
    <script type="text/javascript">
    	function clear(){
    		document.getElementById("btitle").value = "";
    		document.getElementById("bbody").value = "";
    	}
    	
    </script>
</head>
<body>
	<?php include('templates/navBar.html'); ?>
	<h1 align="center">Create new Blog:</h1>

	<div class="container">
		<div class="row">
			<div class="col-sm-8">
				<form method="POST">
				  <label for="blog_title"><h2>Enter Blog title:</h2></label>
		          <input type="text" class="form-control" name="blog_title" placeholder="Enter title here..." id="btitle" pattern=".{4,}" value="<?php if(isset($_POST['preview'])){echo $_POST['blog_title'];} ?>" required/>
		          <h2>Wite body:</h2>
		          <textarea id="bbody" name="blog_body" rows="15" cols="103" style="margin-top: 10px; overflow: scroll;resize: none;" placeholder="write something here..."><?php if (isset($_POST['preview'])) {
		          	echo $_POST['blog_body'];
		          } ?></textarea>
		          <input type="submit" name="upload" class="btn btn-success" value="Upload"/>&nbsp
		          <input type="submit" name="preview" class="btn btn-info" value="preview"/>&nbsp
		          <button class="btn btn-danger" onclick="clear()">discard</button>
			    </form>				
			</div>

			<div class="col-sm-3 col-sm-offset-1 bss">
				<h2 align="center">User Manual</h2>
				<p>This manual provides the do's and don'ts while posting a blog. Please read the following points carefully:</p>
				<p>
					1.Use <b>&ltp&gt and &lt/p&gt</b> to write in a paragraph. use &ltp&gt in the beginning of the paragraph and &lt/p&gt at the end of the paragraph. 
				</p>
				<p>
					2.Use <b>&ltb&gt&lt/b&gt, &lti&gt&lt/i&gt, &ltu&gt&lt/u&gt</b> to bold, italic, underline a word or sentence.
				</p>
				<p>
					3.Use <b>&lttable&gt&lt/table&gt</b> to create a table.
				</p>
				<p>
					4.Use <b>&ltimg src='link' &gt</b> tag to use images in the blog. Provide the link or url of the image in the 'src' attribute.
				</p>
				<p>
					5.Use <b>&lta href='link' &gt click &lt/a&gt </b> to use hyperlinks in the blog. Provide the link or url of the page in the 'href' attribute.
				</p>
				<p>
					6.Use <b>&lth1&gt&lt/h1&gt,&lth2&gt&lt/h2&gt,...&lth6&gt&lt/h6&gt</b> tags for headings. The heading size decreases as the number increases.
				</p>
				<p>
					7.Use <b>&ltul&gt&lt/ul&gt, &ltol&gt&lt/ol&gt</b> for unordered and ordered list respectively. use <b>&ltli&gt item &lt/li&gt</b> for writing list item.
				</p>
				<p>
					8.Use <b>&ltpre&gt&lt/pre&gt</b> for indicating preformatted text.					
				</p>
			</div>
		</div>

		
			<?php

			if (isset($_POST['preview'])) {
				require_once 'C:\WAMP\wamp64\www\blogSpace\htmlpurifier-4.10.0\htmlpurifier-4.10.0\library\HTMLPurifier.auto.php'; 
                $purifier = new HTMLPurifier(); 
                $dirty_html = $_POST["blog_body"];
                $clean_html = $purifier->purify($dirty_html);      
                $char_count = strlen($clean_html);
                echo "character count:".$char_count."<br>";
                echo "<div class='preview_box'>";
                echo $clean_html;
                echo "</div>";       
			}			  
            
			?>		
		
	</div>

	<?php include('templates/footer.html');  ?>

</body>
</html>