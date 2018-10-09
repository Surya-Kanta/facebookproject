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
        <div id="fb-root"></div>
        <script>(function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0&appId=317218968770347&autoLogAppEvents=1';
                fjs.parentNode.insertBefore(js, fjs);
              }(document, 'script', 'facebook-jssdk'));
        </script>
        
        <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
        
        <script src="https://apis.google.com/js/platform.js" async defer></script>

        
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
                        <li class="active-link">
                            <a href="share.php"><i class="fa fa-edit "></i>Share </a>
                        </li>
                    </ul>
                </div>

            </nav>
            <!-- /. NAV SIDE  -->
            <div id="page-wrapper" >
                <div id="page-inner" >
                    <div class="row">
                        <div class="col-md-12">
                            <h2>About</h2>  
                        </div>
                    </div>              
                    <!-- /. ROW  -->
                    <hr />
                    <div class="share" >
                         <!-- Go to www.addthis.com/dashboard to customize your tools --> 
                         <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5b3e720492cd631a"></script>
                        <h3>Share</h3>
                        <div style="align-content: center; size: 50px;">
<!--                            <h3>Share on Facebook</h3>-->
                            <div class="fb-share-button padding" 
                                 data-href="https://coolskrin.000webhostapp.com" data-layout="button_count" data-size="large" data-mobile-iframe="true"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fcoolskrin.000webhost.com%2F&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">Share</a>
                            </div>
                        </div>

                        <div class="padding">
                            <!--<h3>Share on Twitter</h3>-->
                            <div>
                                <a href="https://twitter.com/share?ref_src=twsrc%5Etfw" class="twitter-share-button" data-size="10000" data-text="Hey folks! Here I am sharing a cool app that let the users use the API interface &amp; all Services on Facebook platform. Let&#39;s have a try to it." data-url="https://coolskrin.000webhostapp.com" data-hashtags="FacebookGraphApiProject" data-lang="en" data-dnt="true" data-show-count="false">Tweet</a>

                            </div>
                        </div>

                        <div class="padding">
                            <!--<h3>Share on Whatsapp</h3>-->
                            <div class="wa-share">
                                 <a href="https://wa.me/?text=Hey%20folks!%20Here%20I%20am%20sharing%20a%20cool%20app%20that%20let%20the%20users%20use%20the%20API%20interface%20and%20all%20Services%20on%20Facebook%20platform.%20Lets%20have%20a%20try%20to%20it.%20https://coolskrin.000webhostapp.com" ><img src="./assets/img/whatsapp-share-button.png" alt="Image" /></a>

                            </div>
                            
                        </div>   

                        <div class="padding">
                            <!--<h3>Share on Google+</h3>-->
                            <div class="g-plus" 
                                 data-action="share" data-width="1000" data-height="30" data-href="https://coolskrin.000webhostapp.com">

                            </div>

                        </div> 

                        <!-- Body contents here... -->

                    </div>
                    
                    <div class="details">
                        <h3>Developers</h3>
                        <p>
                            This is an open-source project initially made for Internship report submission. The developers had and great contribution to this project.
                        </p>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>

                                        <tr><th>Developers</th></tr>

                                </thead>
                                <tbody>
                                    <tr class="success"><td>Nibedita Routray</td></tr>

                                    <tr class="active"><td>Surya Kanta Rath</td></tr>
                                </tbody>
                            </table>
                        </div>
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
