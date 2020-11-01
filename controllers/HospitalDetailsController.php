<?php
require_once("../../controllers/BaseController.php");
require_once("../../controllers/Auth.php");
require_once("../../utils/Route.php");

class HospitalDetailsController extends BaseController {
    public function __construct() {
        BaseController::__construct();
        $this->tablename = 'hospital_details';
    }

    public function login() {
        try{
            $username = $_POST['username'];
            $password = md5($_POST['password']);
            $data = $this->getData(['where' => [
                ['username' => $username],
                ['password' => $password],
            ]]);
            if(sizeof($data) > 0){
                $data['userType'] = 'hospital';
                new Auth($data);
            }
        } catch(Exception $e) {
            //Log
        }
    }

    public function register() {
        try{
            $data['name'] = $_POST['name'];
            $data['username'] = $_POST['username'];
            $data['password'] = md5($_POST['password']);
            if(!empty($data)){
                $this->insert($data);
                (new Route('home'))->redirect();
            }
        } catch (Exception $e) {
            //Log
        }
        (new Route('auth',['t'=> 'hospital','f' => 'register']))->redirect();
    }
}