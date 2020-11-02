<?php
$backtrack = $backtrack ?? "../../";
require_once($backtrack."controllers/BaseController.php");
require_once($backtrack."utils/Route.php");

class AuthenticateController extends BaseController{

    private $id;
    private $username;
    private $name;
    private $blood_group;
    private $userType;

    public function __construct(){
        BaseController::__construct();
        if(isset($_COOKIE) && isset($_COOKIE['a_name']) && isset($_COOKIE['a_username']) && isset($_COOKIE['a_id']) && isset($_COOKIE['a_userType']) && ($_COOKIE['a_userType'] == "receiver" && $_COOKIE['a_blood_group'] || $_COOKIE['a_userType'] == "hospital")) {
            $this->id = $_COOKIE['a_id'];
            $this->name = $_COOKIE['a_name'];
            $this->username = $_COOKIE['a_username'];
            $this->userType = $_COOKIE['a_userType'];
            $this->tableName = $this->userType.'_details';
            if($this->userType == "receiver"){
                $this->blood_group = $_COOKIE['a_blood_group'];
            }
        }
    }

    public function setTableAndUserType(){
        parse_str(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY), $queries);
        if( $queries['t'] == 'hospital' || $queries['t'] == 'receiver'){
            $this->tableName = $queries['t'].'_details';
            $this->userType = $queries['t'];
        } else {
            throw new Exception("Invalid type detected, type: ".$queries['t']);
        }
    }

    public function userExists($username){
        try{
            $this->setTableAndUserType();
            $data = $this->getData([ 'fields' => ['COUNT(id) as count'] ,'where' => [
                ['LOWER(username)' => $username]
            ]]);
            return json_encode(['status' => 200, 'data' => ($data['count'] > 0)]);
        } catch(Exception $e) {}
        return json_encode(['status' => 500, 'data' => false ]);
    }

    private function setcookie($data = []){
        if(sizeof($data) > 0){
            if(isset($data) && isset($data['name']) && isset($data['username']) && isset($data['id']) && isset($this->userType) && ($this->userType == "receiver" && $data['blood_group'] || $this->userType)){
                setcookie('a_id',$data['id'],time() + (24 * 60 * 60),'/');
                setcookie('a_name',$data['name'],time() + (24 * 60 * 60),'/');
                setcookie('a_username',$data['username'],time() + (24 * 60 * 60),'/');
                setcookie('a_userType',$this->userType,time() + (24 * 60 * 60),'/');
                $this->id = $data['id'];
                $this->name = $data['name'];
                $this->username = $data['username'];
                $this->userType = $this->userType;
                if($this->userType == "receiver"){
                    $this->blood_group = $data['blood_group'];
                    setcookie('a_blood_group',$data['blood_group'],time() + (24 * 60 * 60),'/');
                }
            }
        }
    }

    public function login() {
        try{
            $this->setTableAndUserType();
            $username = $_POST['username'];
            $password = md5($_POST['password']);
            $data = $this->getData(['where' => [
                ['username' => $username],
                ['password' => $password],
            ]]);
            $this->setcookie($data);
        } catch(Exception $e) {
            //Log
        }
    }

    public function register() {
        try{
            $this->setTableAndUserType();
            $data['name'] = $_POST['name'];
            $data['username'] = $_POST['username'];
            $data['password'] = md5($_POST['password']);
            if($this->userType === 'receiver'){
                $data['blood_group'] = $_POST['blood_group'];
            }
            if(!empty($data)){
                $data['id'] = $this->insert($data);
                $this->setcookie($data);
                // (new Route('home'))->redirect();
            }
        } catch (Exception $e) {
            //Log
        }
        // (new Route('auth',['t'=> 'hospital','f' => 'register']))->redirect();
    }

    public function user(){
        $data = [
            'id' => $this->id,
            'username' => $this->username,
            'name' => $this->name,
            'userType' => $this->userType
        ];
        if($this->userType == 'receiver'){
            $data []= $this->blood_group;
        }
        return $data;
    }

    public function logout(){
        setcookie('a_id',"",time() - 3600,'/');
        setcookie('a_name',"",time() - 3600,'/');
        setcookie('a_username',"",time() - 3600,'/');
        setcookie('a_userType',"",time() - 3600,'/');
        setcookie('a_blood_group',"",time() - 3600,'/');
        $this->id = null;
        $this->name = null;
        $this->username = null;
        $this->userType = null;
        $this->blood_group = null;
    }
}