<?php

session_start();
$_SESSION['parent'] = 'profile';

$conn = mysqli_connect("localhost","root","","blogspacedb");
if (mysqli_connect_error()) {
    die("connection error occured!");
}

$name = $_GET['user_name'];
$email = $_GET['user_email'];

$url = $_SERVER['REQUEST_URI'];
$pos = strpos($url,"/profile");
$pos++;
$link = substr($url,$pos);
//$_SESSION['logged_profile_link'] = $link;

//$_SESSION['profile_url']

if ($_SESSION['logged_user_name'] == $name) {
    $_SESSION['logged_profile_link'] = $link;
}


$sql = "select * from userdetails where user_name='$name' and email_id='$email'";
$result = $conn->query($sql);
$rowcount = mysqli_num_rows($result);
if ($rowcount == 0) {
    echo "no such <b>profile exists</b>";
    die();
}



?>

<!DOCTYPE html>
<html>
<head>
    <title>Profile: <?php echo $name; ?></title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="cstyle.css">
</head>
<body>

    <?php include('templates/navBar.html'); ?>

    <div class="container">
        <div style="float: right;margin-top: 20px;">
            <?php include('templates/searchBar.php'); ?>
        </div>
        
        <div class="row" style="margin-top: 50px;">
            <center>
            <div id="profile_pic" class="col-md-3 profile_pic">
                <?php

                $row = $result->fetch_assoc();
                $link = $row['profile_img'];
                if(isset($link)){
                    ?>

                    <script type="text/javascript">
                        window.onload = function(){
                            document.getElementById("profile_pic").style.backgroundImage = "url('<?php echo $link; ?>')";
                        }
                    </script>

                    <?php
                }

                 ?>
            <?php
            if(isset($_SESSION['logged_user_name']) and $_SESSION['logged_user_name'] == $_GET['user_name']){
                include('templates/profile_pic_upload.html');
            }

            ?>               
                
            </div>

        </center>
            <div class="col-md-8 col-md-offset-1 box">
                <h1><b style="text-decoration: none;"><?php echo $name; ?></b> </h1>
                <i><?php echo $email; ?></i><br>
                <p style="margin-top: 20px;">No. of Blogs:
                    <?php

                    $count_sql = "select count(title) as titles from bloglist where author = ? ";
                    $stmt = $conn->prepare($count_sql);
                    $stmt->bind_param("s",$name);
                    $stmt->execute();
                    $ress = $stmt->get_result();
                    $row = $ress->fetch_assoc();
                    echo $row['titles'];
                    ?>  &nbsp | &nbsp followers:       
                    <?php

                    $count_sql = "select count(follows) as folls from followerlist where follows = ? ";
                    $stmt = $conn->prepare($count_sql);
                    $stmt->bind_param("s",$name);
                    $stmt->execute();
                    $ress = $stmt->get_result();
                    $row = $ress->fetch_assoc();
                    echo $row['folls'];
                    ?>          
                </p>

                <!-- follow feature here -->
                <?php if ($_SESSION['logged_user_name'] != $name) {
                    include('templates/follower.php');
                }
                 ?>
                
            </div>
            
        </div>  

        <div class="row" style="margin-top: 90px;">
        
                <?php 
                if (isset($_SESSION['logged_user_name']) and $_SESSION['logged_user_name'] == $_GET['user_name']) {
                    include('templates/controlpannel.html');
                }
                
                ?>                       
        </div> 
        <h2>Blogs:</h2>

        <div class="row" >
            <?php

            $sql2 = "select * from bloglist where author='$name' order by date desc, time desc";
            $res2 = $conn->query($sql2);
            $row_count = mysqli_num_rows($res2);
            if ($row_count == 0) {
                echo "no blogs yet!";
            }
            else{
                while ($row2 = $res2->fetch_assoc()) {  ?>
                    <div class="blog_box" style="width: 100%">
                      <h3><a href="<?php echo $row2['url']; ?>" ><?php echo $row2['title']; ?></a></h3>
                      <p><?php echo strip_tags($row2['body']); ?></p>
                    </div>

                <?php

                }

            }
            ?>
            

        </div>
        
    </div>

    <?php include('templates/footer.html');  ?>



</body>
</html>

<?php

if (isset($_POST['upload'])) {


    $file = $_FILES['btn_pic_upload'];
    $filename = $_FILES['btn_pic_upload']['name'];
    $file_temp_loc = $_FILES['btn_pic_upload']['tmp_name'];

    $file_ext = explode('.',$filename);
    $file_new_ext = strtolower(end($file_ext));

    $allowed = array('png','jpg','jpeg');
    if (in_array($file_new_ext,$allowed)) {
        $new_file_name = uniqid('',true).".".$file_new_ext;
        $file_dest = 'uploads/'.$new_file_name;
        move_uploaded_file($file_temp_loc,$file_dest);
    }

    $con = mysqli_connect("localhost","root","","blogspacedb");
    if (mysqli_connect_error()) {
        die("connection error occured 2");
    }

    $sql = "update userdetails set profile_img='$file_dest' where user_name='$name' ";
    $result = $con->query($sql);
    $_SESSION['img_name'] = $file_dest;

    if ($result == true) {

        ?>

        <script type="">
            window.onload = function(){
                document.getElementById("profile_pic").style.backgroundImage = "url('<?php echo $file_dest; ?>')";
            }
        </script>

        <?php
        

    }   


}

?>
