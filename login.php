<?php
session_start();
require_once 'src/abraham/twitteroauth.php';
require 'config.php';

//check whether user already logged in with twitter
if (isset($_COOKIE['user_oauth_token_cookie']) && isset($_COOKIE['user_oauth_token_secret_cookie'])) {
    // Redirect to twiiter Timeline
    header('Location: twitter.php?Suc=200');
} else // Not logged in
{
    $connection    = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
    $request_token = $connection->getRequestToken(OAUTH_CALLBACK); //get Request Token

    if ($request_token) {
        $token                            = $request_token['oauth_token'];
        $_SESSION['request_token']        = $token;
        $_SESSION['request_token_secret'] = $request_token['oauth_token_secret'];

        switch ($connection->http_code) {
            case 200:
                $url = $connection->getAuthorizeURL($token);
                //redirect to Twitter .
                header('Location: ' . $url);
                break;
            default:
                echo "Coonection with twitter Failed";
                break;
        }

    } else //error receiving request token
    {
        echo "Error Receiving Request Token";
    }

}
