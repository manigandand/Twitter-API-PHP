<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Twitter - OAuth Login</title>
    <link rel="shortcut icon" href="assets/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" href="assets/style.css">
    <style>
        body {
            padding: 30px;
            background-color: #0f3e53;
        }
        h4 {
            color: rgba(156, 148, 148, 0.88);
        }
        .bg {
            background: #001e3a url("assets/bg.png") no-repeat;
            height: 585px;
            border-radius: 10px;
            box-shadow: #ccc 0px 0px 1px 1px;
        }
        .login {
            display: block;
            font-size: 24px;
            font-weight: bold;
            margin-top: 67px;
            cursor: pointer;
            line-height: 1.5;
        }
    </style>
</head>
<body>
<div class="container bg">
    <header class="clearfix">
        <h1>Twitter <span>display your photo stream</span></h1>
    </header>
    <div class="main">
        <ul class="grid">
            <li><img src="assets/twitter-logo.png" alt="Instagram logo"></li>
            <li>
                <a class="login" href="login.php">» Login with Twitter</a>
                <h4>Use your Twitter account to login.</h4>
            </li>
        </ul>
        <!-- GitHub project -->
        <footer>
            <p>created by <a href="https://github.com/manigandand/Twitter-API-PHP">manigandand's Twitter class</a>, available on GitHub</p>
        </footer>
    </div>
</div>
</body>
</html>
