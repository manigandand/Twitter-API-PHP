<?php
session_start();
require_once 'src/abraham/twitteroauth.php';
require 'config.php';

if (isset($_COOKIE['user_oauth_token_cookie']) && isset($_COOKIE['user_oauth_token_secret_cookie'])) {
    $oauth_token_cookie        = $_COOKIE['user_oauth_token_cookie'];
    $oauth_token_secret_cookie = $_COOKIE['user_oauth_token_secret_cookie'];

    // get user deatils
    $connection                 = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $oauth_token_cookie, $oauth_token_secret_cookie);
    $params                     = array();
    $params['include_entities'] = 'false';
    $content                    = $connection->get('account/verify_credentials', $params);
    // echo "<pre>";
    // print_r($content->errors);
    // check whether the token expired
    if ($content->errors != null) {
        foreach ($content->errors as $value) {
            if ($value->code == 89) {
                // delete all the cookies
                setcookie("user_oauth_token_cookie", "", time() - 3600, "/Twitter-API-PHP");
                setcookie("user_oauth_token_secret_cookie", "", time() - 3600, "/Twitter-API-PHP");
                // redirect to index
                header('Location: login.php');
            }
        }
    } else {
        if ($content && isset($content->screen_name) && isset($content->name)) {
            $_SESSION['user_name']        = $content->name;
            $_SESSION['user_screen_name'] = $content->screen_name;
            $_SESSION['user_location']    = $content->location;
            $_SESSION['user_description'] = $content->description;
            foreach ($content->entities as $val) {
                foreach ($val->urls as $value) {
                    $_SESSION['user_url']          = $value->url;
                    $_SESSION['user_expanded_url'] = $value->expanded_url;
                    $_SESSION['user_display_url']  = $value->display_url;
                }
            }
            $_SESSION['user_followers_count']     = $content->followers_count;
            $_SESSION['user_friends_count']       = $content->friends_count;
            $_SESSION['user_listed_count']        = $content->listed_count;
            $_SESSION['user_total_tweets']        = $content->statuses_count;
            $_SESSION['user_likes_count']         = $content->favourites_count;
            $_SESSION['user_total_tweets']        = $content->statuses_count;
            $_SESSION['user_profile_image']       = $content->profile_image_url_https;
            $str                                  = explode("_normal", $content->profile_image_url_https);
            $_SESSION['user_profile_image_large'] = $str[0] . "_400x400" . $str[1];
            $_SESSION['user_profile_banner_url']  = $content->profile_banner_url;
            $since                                = $content->created_at;
            $date                                 = DateTime::createFromFormat('D M j H:i:s O Y', $since);
            $date->setTimeZone(new DateTimeZone('Asia/calcutta'));
            $_SESSION['user_since']       = $date->format('F Y');
            $_SESSION['user_since_title'] = $date->format('D d M Y - h:i:s A');

            // Redirect to twiiter Timeline
            if ($_GET['Suc'] == 1) {
                header('Location: twitter.php');
            } else {
                header('Location: twitter.php?Suc=200');
            }
        } else {
            // echo "<h4> Login Error / Can't able to verify user details</h4>";
            header('Location: login.php');
        }
    }
    // echo "</pre>";exit();

} else {
    // Redirect to index
    header('Location: index.php?Err=401');
}
