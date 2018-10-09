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
                        <a href="blank.php"></a>
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
                                    } else  {

                                            $permissions = ['email', 'user_age_range', 'user_birthday', 'user_friends', 'user_gender', 'user_hometown', 'user_likes', 'user_link', 'user_location', 'user_photos', 'user_posts', 'user_status', 'user_tagged_places', 'user_videos', 'publish_actions'];

                                            $helper = $fb->getRedirectLoginHelper();
                                            $accessToken = $helper->getAccessToken();

                                            if (isset($accessToken)) {
                                                $url = "https://graph.facebook.com/v2.12/me?fields=id,name,picture&type=&access_token={$accessToken}";
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


                        <li class="active-link">
                            <a href="blank.php"><i class="fa fa-edit "></i>Accounts Info</a>
                        </li>



                        <li>
                            <a href="st_update.php"><i class="fa fa-qrcode "></i>Text Status Update</a>
                        </li>
                        <li>
                            <a href="link_update.php"><i class="fa fa-table "></i>Link Update</a>
                        </li>
                        <li>
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
           
                    <?php
                    /* @var $_GET type */
                    
                    if (isset($_SESSION['accessToken'])) {
                     
                        $url1 = "https://graph.facebook.com/v2.12/me?fields=id,name,picture,email,age_range,birthday,gender,link,hometown,location,languages&access_token={$_SESSION['accessToken']}";
                        $headers1 = array("Content-type: application/json");

                        $ch1 = curl_init();
                        curl_setopt($ch1, CURLOPT_HTTPHEADER, $headers1);
                        curl_setopt($ch1, CURLOPT_URL, $url1);
                        curl_setopt($ch1, CURLOPT_FOLLOWLOCATION, 1);
                        curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch1, CURLOPT_COOKIEJAR, 'cookie.txt');
                        curl_setopt($ch1, CURLOPT_COOKIEFILE, 'cookie.txt');
                        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);

                        
                        $st1 = curl_exec($ch1);
                        $result1 = json_decode($st1, true);
                        
                        
                        
                        } 
                    
                    ?>
<div id="page-wrapper" >
          <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Accounts Section</h2>
                    </div>
                </div>
                <!-- /. ROW  -->
                <hr />
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        
                        <div style="float:left; padding-right: 30px;">
                            <img src="./src/image.jpg" class="img-thumbnail" style="border: 30px; border-radius: 1000px; height: 100px; width: 100px;">
                        </div>
                        <div style="float:left; padding-right: 30px;">
                           <?php
                            echo "<strong>Facebook ID:  </strong>".$_SESSION['id']."<br />";
                            echo "<strong>Name: </strong>".$_SESSION['name']."<br />";
                            echo "<strong>Gender: </strong>".$result1['gender']."<br />";
                            echo "<strong>Email ID: </strong>".$result1['email']."<br />";
                            ?>
                        </div>
                        <br />
                        
                    </div>
                   
                    <div class="col-lg-6 col-md-6" style="float: left;">
                        <?php 
                        echo "<strong>Birthday: </strong>".$result1['birthday']."<br />";
                        echo "<strong>Age Range:    </strong>".$result1['age_range']['min']."<br />";
                        echo "<strong>Hometown: </strong>".$result1['hometown']['name']."<br />";
                        echo "<strong>Current Location: </strong>".$result1['location']['name']."<br />";
                        
                        ?>
                    </div>

                </div>
                <!-- /. ROW  -->
                <hr>
                
                <div class="row">
                    
                    <div class="col-lg-6 col-md-6">
                        
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Languages Known</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    foreach($result1['languages'] as $key1){
                                        echo "<tr class='success'><td>".$key1['name']."</td></tr>";
                                    }
                                    ?>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        
                        <div class="table-responsive">
                            <table class="table" style="float: center;">
                                <thead>
                                    <tr>
                                        <th>Links</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    <tr class="danger">
                                        <td>Facebook</td>
                                        <td><a href="https://www.facebook.com/">Go</a></td>
                                    </tr>
                                    <tr class="alert-info">
                                        <td>Profile</td>
                                        <td><?php echo "<a href='".$result1['link']."'>Go</a>"; ?></td>
                                    </tr>
                                    <tr class="alert-danger">
                                        <td>Logout</td>
                                        <td><a href="index.php?logout=true">Go</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /. ROW  -->
                <hr>


                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                Information
                            </div>
                            <div class="panel-body">
                                <p>Contents</p>
                            </div>
                            
                        </div>
                    </div>
                    

                </div>
                <!-- /. ROW  -->
                
            </div>
            </div>
                  
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
