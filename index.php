<?php

session_start();
$_SESSION['logged_user_name'] = null;

$conn = mysqli_connect("localhost","root","","blogspacedb");
if(mysqli_connect_error()){
	die("error occured");
}

if (isset($_GET['user_signup_submit'])) {

    $email_id = $_GET['user_email'];
    $pass = $_GET['user_psd'];  
	
	  $name = $_GET['user_name'];
	  $url = $_SERVER['REQUEST_URI'];
    $pos = strpos($url,"&user_psd");
    $substr = substr($url,$pos);
    $url2 = str_ireplace($substr,"",$url);

    //$url2  = "/blogtest/testfolder/?user_name=erza&user_email=dsvsd%40gmail.com";
    $pos = strpos($url2,"?user_name");
    //echo $pos."<br>";
    $sub = substr($url2,$pos);
    $profile_url =  "profile.php".$sub;
    //echo $profile_url;

    $sql = "select * from userdetails where user_name='$name' ";
    $result = $conn->query($sql);
    $rowcount = mysqli_num_rows($result);
    if($rowcount > 0){  ?>
    	<script type="text/javascript">
    		window.onload = function(){
    			document.getElementById("wel_box").style.display = 'none';
	            document.getElementById("signup_form_box").style.display = 'block';
	            document.getElementById("login_bxx").style.display = 'none';
    		}
    	</script>
<?php
    }else{

    	$sql2 = "insert into userdetails (user_name,email_id,pass,profile_url) values ('$name','$email_id','$pass','$profile_url')";
    	$res = $conn->query($sql2);
    	if($res == true){
        $_SESSION['logged_user_name'] = $name;
    		header("Location: ".$profile_url);
    	}
    }

}

if (isset($_POST['user_login_submit'])) {

	$name = $_POST['user_name'];
	$pass = $_POST['user_pass'];

	$sql3 = "select * from userdetails where user_name='$name' and pass='$pass'";
	$ress = $conn->query($sql3);
	$rowcount2 = mysqli_num_rows($ress);
	if($rowcount2 == 1){
    $row = $ress->fetch_assoc();
    $url = $row['profile_url'];
    $_SESSION['logged_user_name'] = $name;
    header("Location: ".$url);
	}else if($rowcount2 == 0){ ?>
		<script type="text/javascript">
			window.onload = function(){
					document.getElementById("wel_box").style.display = 'none';
	                document.getElementById("signup_form_box").style.display = 'none';
	                document.getElementById("login_bxx").style.display = 'block';
			}
		</script>

<?php

	}
	
}

$conn->close();

?>

<!DOCTYPE html>
<html>
<head>
<title>blogSpace</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<style>
.box1{
background-image:url("http://pic.51yuansu.com/backgd/cover/00/00/81/5b4d4ae388a50.jpg!/fw/780/quality/90/unsharp/true/compress/true");
background-repeat: no-repeat;
background-size:cover;
height:100vh;
}
.box2{
margin-top:10px;
background-image:url("imgs/oldbooks.jpg");
background-repeat: no-repeat;
background-size:cover;
height:100vh;
}
.box3{
margin-top:10px;
background-image:url("imgs/img_1086253.jpg");
background-repeat: no-repeat;
background-size:cover;
height:100vh;
}
.box4{
margin-top:10px;
background-image:url("imgs/178011925f8d2e9ee3602f47e518-1443303.jpg");
background-repeat: no-repeat;
background-size:cover;
height:100vh;
}



h1{
	color:white;
	font-size: 80px;
	font-family: cursive;
	margin-bottom: 20px;
	
}

.btn_login{
  background-color:  Transparent; 
  border: none;
  color: white;
  padding: 15px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  border: 2px solid white;
  border-radius: 5px;
  margin-top: 10px;
  float: right;
  margin-right: 20px;

}

#sry{
	color: red;

}

.btn_login:hover{
	border: 2px solid yellow ;
	color: yellow;
}

.btn_signup{
  background-color:  Transparent; /* Green */
  border: none;
  color: white;
  padding: 15px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  border: 2px solid white;
  border-radius: 5px;
}
.btn_signup:hover{
	border: 2px solid yellow ;
	color: yellow;
}

#myBtn {
  display: none;
  position: fixed;
  bottom: 20px;
  right: 30px;
  z-index: 99;
  font-size: 18px;
  border: none;
  outline: none;
  background-color: red;
  color: white;
  cursor: pointer;
  padding: 15px;
  border-radius: 4px;
}

#myBtn:hover {
  background-color: #555;
}

.sign_box{
	height: 300px;
	width: 500px;
	background-color: white;
	opacity: 0.85;
	border: 2px solid blue;
	border-radius: 10px;

}
.login_form_box{
	height: 240px;
	width: 400px;
	background-color: white;
	opacity: 0.85;
	border: 2px solid green;
	border-radius: 10px;

}
</style>

<script>
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
  if (document.body.scrollTop > 500 || document.documentElement.scrollTop > 500) {
    document.getElementById("myBtn").style.display = "block";
  } else {
    document.getElementById("myBtn").style.display = "none";
  }
}

function topFunction() {
  document.body.scrollTop = 0; // For Safari
  document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
}

function func1(){
	document.getElementById("wel_box").style.display = 'none';
	document.getElementById("signup_form_box").style.display = 'block';
	document.getElementById("login_bxx").style.display = 'none';
}

function func2(){
	document.getElementById("wel_box").style.display = 'none';
	document.getElementById("signup_form_box").style.display = 'none';
	document.getElementById("login_bxx").style.display = 'block';
}

</script>
<body>
  <button onclick="topFunction()" id="myBtn" title="Go to top">Top</button>

  <div class="box1">
  	<button class="btn_login" onclick="func2()">Login</button>
  	<center style="padding-top: 30vh;">

  	  <div id="wel_box">	
  		<h1>Welcome to blogSpace</h1>
  		<button class="btn_signup" onclick="func1()">Sign Up</button>
  	  </div>

  	  <div id="signup_form_box" style="display:none;">
  	  	<div class="sign_box">
  	  		<form method="get">
  	  			<div class="form-group" style="margin:20px;">
	              <label for="user_name">Select user name: <?php if (isset($_GET['user_signup_submit']) and $rowcount > 0) {
	              	echo "<span id='sry'>*sorry! username unavailable, select new name</span>";
	              } ?> </label>
		          <input type="text" class="form-control" name="user_name" placeholder="Select user name" pattern=".{4,}" title="user name should contain atleast 4 characters" required/>
	            </div>
	            <div class="form-group" style="margin:20px;">
	              <label for="user_email">Enter email-id:</label>
		          <input type="email" class="form-control" name="user_email" placeholder="Enter email id" required/>
	            </div>
	            <div class="form-group" style="margin:20px;">
	              <label for="user_psd">Enter Password:</label>
		          <input type="password" class="form-control" name="user_psd" placeholder="Enter password" required/>
	            </div>
	            <input type="submit" name="user_signup_submit" value="Sign Up" class="btn btn-primary" style="width: 60%;font-style: bold;">  	  			
  	  		</form>  	  		
  	  	</div>  	  	
  	  </div>

  	  <div id="login_bxx" class="login_form_box" style="display: none;">
  	  	<form method="post">
  	  		    <div class="form-group" style="margin:20px;">
	              <label for="user_name">Enter user name:</label>
		          <input type="text" class="form-control" name="user_name" placeholder="Enter user name" pattern=".{4,}" title="user name should contain atleast 4 characters" required/>
	            </div>
	            <div class="form-group" style="margin:20px;">
	              <label for="user_pass">Enter Password:</label>
		          <input type="password" class="form-control" name="user_pass" placeholder="Enter password" required/>
	            </div>
	            <input type="submit" name="user_login_submit" value="Login" class="btn btn-success" style="width: 60%;font-style: bold;">  
	            <?php if (isset($_POST['user_login_submit']) and $rowcount2 == 0) {
	            	echo "<br><span id='sry'>*wrong credentials! try again</span>";
	            } ?>	  		
  	  	</form>  	  	
  	  </div>
  	</center>
  </div>
 

  <div class="box2">
  	<center style="padding-top: 30vh;">
  	  <div id="wel_box">	
  		<h1 style="font-size: 100px;">share your thoughths :) </h1>
  	  </div>
  	</center>
  </div>
  <div class="box3">
  	<center style="padding-top: 30vh;">
  	  <div id="wel_box">	
  		<h1 style="font-size: 100px;">explore new worlds!</h1>
  	  </div>
  	</center>
  </div>
  <div class="box4">
  	<center style="padding-top: 30vh;">
  	  <div id="wel_box">	
  		<h1 style="font-size: 100px;">Sign Up to get Started ;)</h1>
  	  </div>
  	</center>
  </div>



</body>
</html>