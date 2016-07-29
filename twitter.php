<?php
session_start();
require_once 'src/TwitterAPIExchange1.php';
require 'config.php';
// check whether this is vaild request
if (!isset($_SESSION['name']) && !isset($_SESSION['user_screen_name'])) {
    // Redirect to index
    header('Location: getUserData.php');
}
/** Set access tokens here - see: https://dev.twitter.com/apps/ **/

$settings = array(
    'oauth_access_token'        => $_COOKIE['user_oauth_token_cookie'],
    'oauth_access_token_secret' => $_COOKIE['user_oauth_token_secret_cookie'],
    'consumer_key'              => CONSUMER_KEY,
    'consumer_secret'           => CONSUMER_SECRET,
);
$url           = "https://api.twitter.com/1.1/statuses/user_timeline.json";
$requestMethod = "GET";

$user = $_SESSION['user_screen_name'];
if ($_SESSION['user_total_tweets'] > 3200) {
    $count = 3200;
} else {
    $count = $_SESSION['user_total_tweets'];
}
$getfield = "?screen_name=$user&count=$count&include_rts=1";
$twitter  = new TwitterAPIExchange($settings);
$string   = json_decode($twitter->setGetfield($getfield)
        ->buildOauth($url, $requestMethod)
        ->performRequest(), $assoc = true);

if ($string["errors"][0]["message"] != "") {
    echo "<h3>Sorry, there was a problem.</h3>
    <p>Twitter returned the following error message:</p>
    <p><em>" . $string[errors][0]["message"] . "</em></p>";
    exit();
}
// echo "<pre>";
// print_r($string);
// exit();
// echo "</pre>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Twitter Timeline</title>
    <link rel="shortcut icon" href="assets/favicon.ico" type="image/x-icon" />
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link href="assets/style.css" rel="stylesheet">
    <style>
        body {
            background-image: none!important;
            background-color: #f5f8fa!important;
            line-height: 1.375;
            min-width: 936px;
        }
        header {
          width: 100%;
          -webkit-backface-visibility: hidden;
          backface-visibility: hidden;
          border-bottom: 1px solid rgba(0,0,0,0.15);
          position: fixed;
          top: 0;
          right: 0;
          left: 0;
          z-index: 1000;
          background-color: #FFFFFF;
        }
        .container {
            position: inherit;
            height: 2000px;
        }
        .txtRight{
            text-align: right;
        }
    </style>
</head>
<body>
<header class="clearfix">
    <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 nav-pad">
         <img src="assets/home.png" class="home-icon" alt="Home">
         <span class="text"> Home</span>
    </div>
    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 txtRight">
        <span><i class="fa fa-twitter twitter-fa" title="Back to top↑"></i></span>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 txtRight">
         <div class="tweet-div">
            <img src="<?php echo $_SESSION['user_profile_image']; ?>" class="size32" alt="Profile Avatar">
         </div>
         <div class="tweet-div">
             <img src="assets/tweet.png" class="tweet-icon" alt="Tweet">
         </div>
         <div class="tweet-div">
             <a href='logout.php'><i class="fa fa-sign-out signout-fa" aria-hidden="true"></i>
             </a>
         </div>
    </div>
</header>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="position: relative;padding: 0;">
    <img src="<?php echo $_SESSION['user_profile_banner_url']; ?>" class="img-responsive" alt="Banner" style="height: 345px;width: 100%;">
</div>
<!-- user engage details-->
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 ProfileCanopy-navBar">
    <div class="ProfileNav-stat">
        <span class="ProfileNav-label">Tweets</span>
        <span class="ProfileNav-value"><?php echo $_SESSION['user_total_tweets']; ?></span>
    </div>
    <div class="ProfileNav-stat">
        <span class="ProfileNav-label">Following</span>
        <span class="ProfileNav-value"><?php echo $_SESSION['user_friends_count']; ?></span>
    </div>
    <div class="ProfileNav-stat">
        <span class="ProfileNav-label">Followers</span>
        <span class="ProfileNav-value"><?php echo $_SESSION['user_followers_count']; ?></span>
    </div>
    <div class="ProfileNav-stat">
        <span class="ProfileNav-label">Likes</span>
        <span class="ProfileNav-value"><?php echo $_SESSION['user_likes_count']; ?></span>
    </div>
    <div class="ProfileNav-stat">
        <span class="ProfileNav-label">Lists</span>
        <span class="ProfileNav-value"><?php echo $_SESSION['user_listed_count']; ?></span>
    </div>
</div>
<div class="container">
    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
        <div class="ProfileCanopy-avatar">
            <div class="ProfileAvatar">
                <img src="<?php echo $_SESSION['user_profile_image_large']; ?>" class="size200" alt="Profile Avatar">
            </div>
        </div>
        <div class="ProfileHeaderCard">
            <h1 class="ProfileHeaderCard-name">
                <a href="https://twitter.com/<?php echo $_SESSION['user_screen_name']; ?>" class="ProfileHeaderCard-nameLink"><?php echo $_SESSION['user_name']; ?></a>
            </h1>
            <h2 class="ProfileHeaderCard-screenname">
                <a href="https://twitter.com/<?php echo $_SESSION['user_screen_name']; ?>" class="ProfileHeaderCard-screenname1">@<span><?php echo $_SESSION['user_screen_name']; ?>
                </span></a>
            </h2>
            <p class="ProfileHeaderCard-bio"><?php echo $_SESSION['user_description']; ?></p>
            <div class="iconsall">
                <span class="fa fa-map-marker" style="font-size: 20px;"></span>
                <span class="iconsvalue" style="padding-left: 7px;">
                <?php echo $_SESSION['user_location']; ?></span>
            </div>
            <div class="iconsall">
                <span class="fa fa-link"></span>
                <span class="iconsvalue">  <a class="iconsvalue" target="_blank"
                href="<?php echo $_SESSION['user_url']; ?>"
                title="<?php echo $_SESSION['user_expanded_url']; ?>">
                <?php echo $_SESSION['user_display_url']; ?></a>
                </span>
            </div>
            <div class="iconsall">
                <span class="fa fa-calendar"></span>
                <span class="iconsvalue" title="<?php echo $_SESSION['user_since_title']; ?>">Joined <?php echo $_SESSION['user_since']; ?></span>
            </div>
            <!--<div class="iconsall">
                <span class="fa fa-birthday-cake"></span>
                <span class="iconsvalue">Born on </span>
            </div>-->
        </div>
    </div>
    <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
        <div class="tweet-main">
        <?php foreach ($string as $value) {
    $date = DateTime::createFromFormat('D M j H:i:s O Y', $value['created_at']);
    $date->setTimeZone(new DateTimeZone('Asia/calcutta'));
    ?>
            <div class="tweet">
                <div class="tweet-content">
                    <img class="tweet-avatar" src="<?php echo $_SESSION['user_profile_image']; ?>">
                    <strong class="fullname"><?php echo $_SESSION['user_name']; ?></strong>
                    <span class="username">@<b><?php echo $_SESSION['user_screen_name']; ?></b></span>
                    <small class="username" title="<?php echo $date->format('D d M Y - h:i:s A'); ?>"><?php echo "&nbsp . &nbsp " . $date->format('d M Y'); ?>
                    </small>
                    <p class="TweetTextSize"><?php echo $value['text']; ?></p>
                        <?php foreach ($value['extended_entities']['media'] as $med) {
        $media_expand_url = $med['expanded_url'];
        $media_source     = $med['media_url_https'];
        $media_type       = $med['type'];
        echo "<div class=\"AdaptiveMedia\">";
        if ($media_type == "photo") {
            echo "<a href=\"{$media_expand_url}\" class=\"tweet-img-url\" target=\"_blank\">";
            echo "<img src=\"{$media_source}\" class=\"media_image\"></a>";
        } else {
            $video_source = $med['video_info']['variants'][1]['url'];
            echo "<video width=\"100%\" controls><source src=\"{$video_source}\" type=\"video/mp4\"></video>";
        }
        echo "</div>";
    }?>
                    <div class="stream-item-footer">
                        <div class="ProfileTweet-action">
                            <span class="fa fa-reply footer-icon" title="reply"></span>
                        </div>
                        <div class="ProfileTweet-action retweet-icon">
                            <span class="fa fa-retweet"></span>
                            <span class="u-hiddenVisually"><?php echo $value['retweet_count']; ?>
                            </span>
                        </div>
                         <div class="ProfileTweet-action favorite-icon">
                            <span class="fa fa-heart"></span>
                            <span class="u-hiddenVisually"><?php echo $value['favorite_count']; ?>
                            </span>
                        </div>
                        <div class="ProfileTweet-action">
                            <span class="fa fa-bar-chart footer-icon"></span>
                        </div>
                        <div class="ProfileTweet-action">
                            <span class="fa fa-ellipsis-h footer-icon"></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php }?>
        </div>
    </div>
</div>


<!-- javascript -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script>
$(document).ready(function(){
    <?php
if ($_GET['Suc'] == 200) {?>
        alert("Welcome back <?php echo $_SESSION['user_name']; ?> ");
    <?php } elseif ($_GET['Suc'] != 200) {?>
        alert("Welcome <?php echo $_SESSION['user_name']; ?> ");
   <?php }?>

    $(".twitter-fa").click(function(){
        $('html, body').animate({scrollTop:40}, 'slow');
    });
});
</script>

</body>
</html>
