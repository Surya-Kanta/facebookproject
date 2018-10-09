<?php
session_start();
require 'main.php';
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Facebook API</title>
        <!-- BOOTSTRAP STYLES-->
        <link href="assets/css/bootstrap.css" rel="stylesheet" />
        <!-- FONTAWESOME STYLES-->
        <link href="assets/css/font-awesome.css" rel="stylesheet" />
        <!-- CUSTOM STYLES-->
        <link href="assets/css/custom.css" rel="stylesheet" />
        <!-- GOOGLE FONTS-->
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    </head>
    <body>

        <div id="wrapper">
            <div class="navbar navbar-inverse navbar-fixed-top">
                <div class="adjust-nav">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="index.php">
                            <img src="src/logo.png" />
                        </a>
                    </div>

                                  <p class="header_name">Friendsbook using Facebook</p>

                </div>
            </div>
            <!-- /. NAV TOP  -->
            <nav class="navbar-default navbar-side" role="navigation">
                <div class="sidebar-collapse">
                    <ul class="nav" id="main-menu">

                        <li>
                            <div class="user-img-div">
                                <div class="user-img">
                                    <?php if (isset($_SESSION['accessToken'])) {
                                        echo '<img src="' . $_SESSION['img'] . '" class="img-thumbnail" />';
                                    } ?> 
                                </div>
                                <div class="user-log">
                                    <?php
                                    if (isset($_SESSION['accessToken'])) {
                                        echo '<a style="text-align:center; color:#FFF;" href="index.php?logout=true" class="glyphicon glyphicon-log-out"><br/>LogOut</a>';
                                    } else {

                                            $permissions = ['email', 'user_age_range', 'user_birthday', 'user_friends', 'user_gender', 'user_hometown', 'user_likes', 'user_link', 'user_location', 'user_photos', 'user_posts', 'user_status', 'user_tagged_places', 'user_videos', 'publish_actions'];

                                            $helper = $fb->getRedirectLoginHelper();
                                            $accessToken = $helper->getAccessToken();

                                            if (isset($accessToken)) {
                                                $url = "https://graph.facebook.com/v2.12/me?fields=id,name,picture,email,age_range,birthday,gender,hometown,location,languages&access_token={$accessToken}";
                                                $headers = array("Content-type: application/json");

                                                $ch = curl_init();
                                                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                                                curl_setopt($ch, CURLOPT_URL, $url);
                                                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                                                curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
                                                curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
                                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                                                $_SESSION['img'] = './src/image.jpg';
                                                $st = curl_exec($ch);
                                                $result = json_decode($st, true);
                                                $_SESSION['id'] = $result['id'];
                                                $_SESSION['name'] = $result['name'];
                                                $content = file_get_contents($result['picture']['data']['url']);
                                                file_put_contents($_SESSION['img'], $content);
                                                $_SESSION['accessToken'] = (string) $accessToken;
                                            } else {
                                                $loginUrl = $helper->getLoginUrl('https://localhost/facebookproject/index.php', $permissions);
                                                echo '<a style="text-align:center; color:#FFF;" href="' . $loginUrl . '" class="glyphicon glyphicon-log-in"><br/>LogIn</a>';
                                            }
                                        
                                        if (isset($_GET['code'])) {
                                            header('location: ./');
                                        }
                                    }
                                    ?>                                
                                </div>
                                <div class="inner-text">
                                    <?php if (isset($_SESSION['accessToken'])) {
                                        echo $_SESSION['id'] . "<br />" . $_SESSION['name'];
                                    } else echo "You are not Logged In..."; ?>
                                </div>
                            </div>
                        </li>
                        <li >
                            <a href="index.php" ><i class="fa fa-desktop "></i>Dashboard</a>
                        </li>


                        <li >
                            <a href="blank.php"><i class="fa fa-edit "></i>Accounts Info</a>
                        </li>



                        <li >
                            <a href="st_update.php"><i class="fa fa-qrcode "></i>Text Status Update</a>
                        </li>
                        
                        <li>
                            <a href="link_update.php"><i class="fa fa-table "></i>Link Update</a>
                        </li>
                        <li class="active-link">
                            <a href="photo_update.php"><i class="fa fa-edit "></i>Photo Update</a>
                        </li>
                        <li>
                            <a href="get_status.php"><i class="fa fa-bar-chart-o"></i>Timeline</a>
                        </li>

                        <li>
                            <a href="friends.php"><i class="fa fa-edit "></i>See Friends</a>
                        </li>
                        <li>
                            <a href="get_photos.php"><i class="fa fa-edit "></i>See Photos</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-edit "></i>My Link Eight </a>
                        </li>
                        <li>
                            <a href="live_api.php"><i class="fa fa-edit "></i>Generate Live Key </a>
                        </li>
                        <li>
                            <a href="share.php"><i class="fa fa-edit "></i>Share </a>
                        </li>
                    </ul>
                </div>

            </nav>
            <!-- /. NAV SIDE  -->
            <div id="page-wrapper" >
                <div id="page-inner">
                    <div class="row">
                        <div class="col-md-12">
                            <h2><?php if(isset($_SESSION['accessToken'])) {echo $_SESSION['name'] . ', you can update Status to your Profile here..'; } else {echo "Login to Continue"; } ?></h2>  
                        </div>
                    </div>              
                    <!-- /. ROW  -->
                    <hr />


                    <!-- Body contents here... -->
                    <div id="post" >
                        

                        <form class="form-group" method="post" >
                            <p>Picture Update here:  <br />
                                <textArea class="form-group-lg" name="message" placeholder="Message" style="height: 200px;width: 885px;"></textArea>
                                <input class="form-control" type="photo" name="photo" placeholder="Link of Image">
                                <input class="btn btn-success" type="submit" value="Post"></p>
                        </form>  
                        <?php
                        if (isset($_POST['message']) && isset($_POST['photo'])) {

                            $data = array("message" => $_POST['message'], "source" => $fb->fileToUpload($_POST['photo']));
                            $request = $fb->post('/me/photos', $data, $_SESSION['accessToken']);
                            $respond = $request->getGraphUser();
                            echo "Successfully posted PHOTO on Facebook with ID: " . $respond['id'];
                        }
                        ?>


                        </div>





                        <!-- /. ROW  -->           
                        </div>
                        <!-- /. PAGE INNER  -->
                        </div>
                        <!-- /. PAGE WRAPPER  -->
                        </div>
                        <div class="footer">


                            <div class="row">
                                <div class="col-lg-12" >
                                    &copy;  2018 Sky Bit | Design by: Sky Bit Bhubaneswar
                                </div>
                            </div>
                        </div>


                        <!-- /. WRAPPER  -->
                        <!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
                        <!-- JQUERY SCRIPTS -->
                        <script src="assets/js/jquery-1.10.2.js"></script>
                        <!-- BOOTSTRAP SCRIPTS -->
                        <script src="assets/js/bootstrap.min.js"></script>
                        <!-- CUSTOM SCRIPTS -->
                        <script src="assets/js/custom.js"></script>


                        </body>
                        </html>
