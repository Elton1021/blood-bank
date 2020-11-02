<?php
require_once("../../controllers/BaseController.php");
require_once("../../utils/Route.php");

class ReceiverDetailsController extends BaseController {
    public function __construct() {
        BaseController::__construct();
        $this->tablename = 'receiver_details';
    }

    public function userExists($username){
        try{
            $data = $this->getData([ 'fields' => ['COUNT(id) as count'] ,'where' => [
                ['LOWER(username)' => $username]
            ]]);
            return json_encode(['status' => 200, 'data' => ($data['count'] > 0)]);
        } catch(Exception $e) {}
        return json_encode(['status' => 500, 'data' => false ]);
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
            $data['blood_group'] = $_POST['blood_group'];
            $data['password'] = md5($_POST['password']);
            if(!empty($data)){
                $this->insert($data);
                (new Route('home'))->redirect();
            }
        } catch (Exception $e) {
            //Log
        }
        (new Route('auth',['t'=> 'receiver','f' => 'register']))->redirect();
    }
}