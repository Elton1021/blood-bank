<?php
require_once('../../controllers/AuthenticateController.php');

$auth = new AuthenticateController();

$auth->login();