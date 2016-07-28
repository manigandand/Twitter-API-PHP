<?php
require_once 'src/TwitterAPIExchange1.php';
date_default_timezone_set('Asia/calcutta');
/** Set access tokens here - see: https://dev.twitter.com/apps/ **/
$settings = array(
    'oauth_access_token'        => "261196046-OdPW3pUPITkTo5C4wOP3EVqNOVtF9rFk1JRRfN2U",
    'oauth_access_token_secret' => "kiDg6yzGBS05VZUO2iD92htFjkZyofOW5j5onUDfkvO0O",
    'consumer_key'              => "KuNs26yYa5re5uJ6HmaC47Pfs",
    'consumer_secret'           => "zKk7i9mX3Q5tLx9ChYrgFZa13pybU71vh7eHDHezNebObYEo4G",
);
$url           = 'https://api.twitter.com/1.1/search/tweets.json';
$requestMethod = "GET";
$count         = 20;

if (isset($_GET['hasTag'])) {
    $hasTag = $_GET['hasTag'];
} else {
    $hasTag = "Kabali";
}
echo "<body style='font-family: Lato, Calibri, Arial, sans-serif;background: #f9f9f9;
    font-weight: 300;font-size: 15px;color: #333;overflow: scroll;overflow-x: hidden;'>";
echo "<div style='
    text-align: center;
    font-size: -webkit-xxx-large;
    position: fixed;
    top: 5px;
    margin: -5px 0%;
    width: 100%;
    z-index: 999999;
    background: #55acee;
    color: #000;
    padding: 10px;
    left: 0px;
    font-weight: bold;'>Top Tweet about: <span style='color:#FFF;'>#" . $hasTag . "</span></div>";
$getfield = '?q=%23' . $hasTag . '&result_type=recent&count=' . $count;

$twitter = new TwitterAPIExchange($settings);
// $string  = json_decode($twitter->setGetfield($getfield)
//         ->buildOauth($url, $requestMethod)
//         ->performRequest());

$string = $twitter->setGetfield($getfield)
    ->buildOauth($url, $requestMethod)
    ->performRequest();

$string = json_decode($string, true);
$key    = "statuses";
foreach ($string['statuses'] as $items) {
    $date = DateTime::createFromFormat('D M j H:i:s O Y', $items['created_at']);
    $date->setTimeZone(new DateTimeZone('Asia/calcutta'));

    echo "<div style='margin: 100px 30%;'>";
    echo "<div style='padding: 20px 20px 11.6px 20px;overflow: hidden;cursor: pointer;background-color: #fff;border: 1px solid #e1e8ed;border-radius: 4px;max-width: 520px;display: block;background: 0 0;font: normal normal 16px/1.4 Helvetica,Roboto,Calibri,sans-serif;color: #1c2022;margin-bottom: 10px;'>";

    echo "<img src='assets/twitter.png' style='float: right;width: 100px;position: relative;top: -15px;right: -15px;'>";
    echo "<img src='" . $items['user']['profile_image_url'] . "' style='float: left;position: relative;border-radius: 4px;width: 36px;height: 36px;top: -10px;'>";
    echo "<span data-scribe='element:name' style='font-weight: bold;margin-top: 2px;max-width: 100%;overflow: hidden!important;padding-left: 10px;up vote 40 down vote accepted display: block;up vote 40 down vote accepted display: block;display: block;height: 24px;position:relative;top: -11px;'>" . $items['user']['name'] . "</span>";
    echo "<span style='margin-top: 2px;max-width: 100%;overflow: hidden!important;padding-left: 10px;display: block;height: 24px;position: relative;top: -17px;font-size: 14px;color: #697882;'>@" . $items['user']['screen_name'] . "</span>";

    echo "<p style='margin-top: -5px;'>" . $items['text'] . "</p>";
    echo "<p style='margin-top: -8px;color: #697882;margin-bottom: 10px;'>" . $date->format('D M d h:i:s A Y') . "</p>";
    echo "<div style=' color: #697882;'>
            <img src='assets/retweet.png' style='width: 25px;height: 25px;'><span style='position: relative;
    top: -7px;padding: 5;margin-right: 15px;'>" . $items['retweet_count'] . "</span>
            <img src='assets/fa.png' style='width: 25px;height: 25px;'><span style='position: relative;
    top: -7px;padding: 5;margin-right: 15px;'>" . $items['favorite_count'] . "</span>
    </div>";
    echo "</div>";
    echo "</div>";
    echo "</body>";
    // echo "<pre>";
    // print_r($items);
    // echo "</pre>";

}
