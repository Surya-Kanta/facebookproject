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
        <!--  JQUERY     -->
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
    </head>
    <body>
        <div id="fb-root"></div>
        <script>(function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0&appId=317218968770347&autoLogAppEvents=1';
                fjs.parentNode.insertBefore(js, fjs);
              }(document, 'script', 'facebook-jssdk'));
        </script> 
                
        <script type="text/javascript">
            $(function() {
                $('.chkdAll').click(function() {
                    $('.chkdPost').prop('checked', this.checked);
                });
            });
        </script>
        
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

                                            $permissions = ['email', 'user_age_range', 'user_birthday', 'user_friends', 'user_gender', 'user_hometown', 'user_likes', 'user_link', 'user_location', 'user_photos', 'user_posts', 'user_status', 'user_tagged_places', 'user_videos'];

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
                        <li>
                            <a href="st_update.php"><i class="fa fa-qrcode "></i>Text Status Update</a>
                        </li>
                        <li>
                            <a href="link_update.php"><i class="fa fa-table "></i>Link Update</a>
                        </li>
                        <li>
                            <a href="photo_update.php"><i class="fa fa-edit "></i>Photo Update</a>
                        </li>
                        <li class="active-link">
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
                            <h2><?php if(isset($_SESSION['accessToken'])) {echo $_SESSION['name'] . ', your Status Timeline is here...'; } else {echo "Login to Continue"; } ?></h2>  
                        </div>
                    </div>              
                    <!-- /. ROW  -->
                    <hr />
                    <div class="data">
    <!--                    <form method='post'>
                            <input class="btn-danger btn-3" style="border-radius:10px;" type="submit" value="Execute Selected" onclick="alert('Are you sure??');"/><hr />
                            <input type="checkbox" class="chkdAll" name="Check All" /><br /><br/>-->
                        <?php
                        if(isset($_POST['chkdPost'])) {
                       
                        //    foreach($_POST['chkdPost'] as $key1[]) {
                                
                                    $fb->setDefaultAccessToken($_SESSION['accessToken']);
                                    $delPhoto = $fb -> delete('/'.$_POST['chkdPost']);
                                    $delResp = $delPhoto ->getGraphNode() -> asArray();
                                    echo "Successfully deleted ".count($_POST['chkdPost']). " posts from Facebook";
                        //        }
                            }
                            ?>
                        <form method='post'>
                            <div style="margin:5px;align-content: center; justify-content: flex-end; float: right;" class="btn-toolbar">
                            <div class="btn-group">
                                <button class="btn btn-success">Execute Selected</button>
                                <button data-toggle="dropdown" class="btn btn-success dropdown-toggle"><span class="caret"></span></button>
                                <ul class="dropdown-menu">
                                    <li><button type="submit" name="Delete" style="color: #ffffff; background-color: #33ff99; text-height:14;  width: 191px; height: 40px;" onclick="alert('Are you sure to Delete these items?');">Delete</button></li>

                                      <li class="divider"></li>
                                      <li><a href="#" >More options coming soon</a></li>
                                </ul>
                            </div>
                        </div>
                            <div style="width: 70%;">
                        <?php
                        try {
                            $fb->setDefaultAccessToken($_SESSION['accessToken']);
                            $posts_request = $fb->get('/me/posts?fields=permalink_url,message,created_time&limit=10&since=01-01-2018');
                            $total_posts = array();
                            $posts_response = $posts_request->getGraphEdge();

                            $response_array = $posts_response->asArray();
                            $total_posts = array_merge($total_posts, $response_array);
                            foreach ($total_posts as $key) {
                                
                                echo '<input type="checkbox" name="chkdPost" value="'.$key['id'].'"><strong>Post ID: ' . $key['id'] . '</strong><br />';
                                
                                echo $key['created_time']->format('d - M - Y - h:m:s A')."<br />";
                                
                                echo '<div class="fb-post" 
                                        data-href="'.$key['permalink_url'].'" data-width="350" data-show-text="true"><blockquote cite="'.$key['permalink_url'].'" class="fb-xfbml-parse-ignore"><p>Video live 3</p>Posted by <a href="#" role="button">Surya Kanta</a> on&nbsp;<a href="'.$key['permalink_url'].'">Monday, July 2, 2018</a></blockquote>
                                        </div>';

                                echo '<div class="fb-comments" 
                                        data-href="'. $key['permalink_url']. '" data-width="50" data-numposts="2">
                                            </div>';
                                
                                echo '<br /><br />';
                            }

                        } catch (Exception $ex) {
                            $ex->getCode();
                        }
                        ?>
                            </div>
                            
                        </form>
    
                        <div class="commAll">
                                <form method="post" action="get_status.php">
                                    <input class="form-control-lg" type="text" name="commentAll" placeholder="Comment to all Post"><br />
                                        <button class="btn btn-primary btn-lg" type="submit">Comment All</button>
                                </form>
                        </div>
                        <?php
                        if(isset($_POST['commentAll'])){
                            foreach($total_posts as $key2) {
                                $fb-> setDefaultAccessToken($_SESSION['accessToken']);
                                $rec = '/'.$key2['id'].'/comments';
                                $dat = array("message" => $_POST['commentAll']);
                                $pc = $fb -> post ($rec,$dat);
                                $resp= $pc ->getGraphNode() ->asArray();
                                echo $resp['id'];
                                
                            }
                        }
                        
                        ?>
                            
                
                        <!-- Body contents here... -->

                        
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
