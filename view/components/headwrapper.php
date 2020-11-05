<?php
    $BACKTRACK = "../";
    require_once($BACKTRACK.'utils/Route.php');
    require_once($BACKTRACK.'controllers/AuthenticateController.php');
    $auth = new AuthenticateController;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Bank</title>
    <link rel="icon" href="../resources/img/fav.jpg" type="image/x-icon">
    <link rel="stylesheet" href="../resources/css/style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body class="custom-bg-dark">
<div class="wrapper" style="min-height:100%;height:auto;">
    <?php
        //incase connection couldn't be established with the db
        if($auth->connectionError){
            $disableNav = true;
            require_once('components/navbar.php');
            ?>
            <div class="container mt-5 mb-5">
                <div class="card bg-dark text-white shadow">
                    <div class="card-body text-center">
                        <span class="material-icons" style="font-size:100px!important;">
                            cloud_off
                        </span>
                        <p>Connection couldn't be established with the database. Try again after few minutes.</p>
                        <button class="btn btn-primary" onclick="location.reload()">Refresh</button>
                    </div>
                </div>
            </div>
            <?php
            require_once('components/footwrapper.php');
            exit();
        }
        require_once('components/navbar.php');
    ?>
    <div id="toast-container" style="min-height:100px;position:fixed;top:0;left:0;z-index:9999;width:100%;margin-top:50px;">
    </div>