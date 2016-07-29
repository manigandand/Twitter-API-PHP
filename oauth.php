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
        setcookie('user_oauth_token_cookie', $access_token['oauth_token'], time() + 60 * 60 * 24 * 365, '/Twitter-API-PHP');
        setcookie('user_oauth_token_secret_cookie', $access_token['oauth_token_secret'], time() + 60 * 60 * 24 * 365, '/Twitter-API-PHP');
        // ---------**************************************************---------
        header('Location: getUserData.php?Suc=1');
    } else {
        echo "<h4> Login Error / Can't verify auth token</h4>";
        eader('Location: index.php?Err=402');
    }
} else //Error. redirect to Login Page.
{
    eader('Location: index.php?Err=402');

}
