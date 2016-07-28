<?php
session_start();
require_once 'src/abraham/twitteroauth.php';
require 'config.php';

if (isset($_GET['oauth_token'])) {

    $connection   = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['request_token'], $_SESSION['request_token_secret']);
    $access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
    // echo "<pre>";
    // print_r($access_token);
    if ($access_token) {
        // save oauth_token & oauth_token_secret in session / cookie / database
        // ---------**************************************************---------
        $_SESSION['user_oauth_token']        = $access_token['oauth_token'];
        $_SESSION['user_oauth_token_secret'] = $access_token['oauth_token_secret'];
        setcookie('user_oauth_token_cookie', $access_token['oauth_token'], time() + 60 * 60 * 24 * 365, '/');
        setcookie('user_oauth_token_secret_cookie', $access_token['oauth_token_secret'], time() + 60 * 60 * 24 * 365, '/');
        // if (isset($_COOKIE['user_oauth_token_cookie']) && isset($_COOKIE['user_oauth_token_secret_cookie'])) {
        //     echo $_COOKIE['user_oauth_token_cookie'] . "<br />";
        //     echo $_COOKIE['user_oauth_token_secret_cookie'] . "<br />";
        // }
        // ---------**************************************************---------
        // get user deatils
        $connection                 = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
        $params                     = array();
        $params['include_entities'] = 'false';
        $content                    = $connection->get('account/verify_credentials', $params);
        // print_r($content);
        // echo "</pre>";
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

            // echo '<pre>';
            // var_dump($_SESSION);
            // echo '</pre>';

            //redirect to main page.
            header('Location: twitter.php');

        } else {
            echo "<h4> Login Error / Can't able to verify user details</h4>";
        }
    } else {

        echo "<h4> Login Error / Can't verify auth token</h4>";
    }

} else //Error. redirect to Login Page.
{
    header('Location: http://hayageek.com/examples/oauth/twitter/login.html');

}
