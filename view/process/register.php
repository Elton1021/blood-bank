<?php
require_once('../../controllers/AuthenticateController.php');

$auth = new AuthenticateController();

if(isset($_POST['userExists']) && boolval($_POST['userExists'])){
    echo $auth->userExists($_POST['username']);
} else {
    $auth->register();
}