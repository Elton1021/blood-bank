<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$BACKTRACK = $BACKTRACK ?? "../../";
require_once($BACKTRACK."utils/MysqliUtil.php");
require_once($BACKTRACK."utils/Log.php");
require_once($BACKTRACK."utils/Route.php");

class AuthenticateController extends MysqliUtil{

    private $id;
    private $username;
    private $name;
    private $blood_group;
    private $userType;

    public function __construct(){
        MysqliUtil::__construct();
        $this->log = new Log;
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

    public function __destruct(){
        MysqliUtil::__destruct();
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
            return json_encode(['status' => 200, 'data' => ($data[0]['count'] > 0)]);
        } catch(Throwable | Error | Exception $e) {
            $this->log->error($e);
        }
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
                ['password' => $password]
            ]]);
            if(sizeof($data) > 0){
                $this->setcookie($data[0]);
                (new Route('home'))->redirect();
            }
            $data = $this->getData(['where' => [
                ['username' => $username]
            ]]);
            if(sizeof($data) > 0){
                $_SESSION['incorrect_password'] = true;
                $_SESSION['username'] = $username;
            } else {
                $_SESSION['incorrect_username'] = true;
            }
        } catch(Throwable | Error | Exception $e) {
            $this->log->error($e);
        }
        if(isset($this->userType)){
            (new Route('auth',['t'=> $this->userType,'f' => 'login']))->redirect();
        }
        (new Route('home'))->redirect();
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
            $userExist = json_decode($this->userExists($data['username']));
            if(!empty($data) && !$userExist->data){
                $data['id'] = $this->insert($data);
                if(!isset($data['id'])){
                    throw new Exception('user not registered');
                }
                $this->setcookie($data);
                (new Route('home'))->redirect();
            }
            throw new Exception('data is inappropriate');
        } catch(Throwable | Error | Exception $e) {
            $this->log->error($e);
        }
        if(isset($this->userType)){
            (new Route('auth',['t'=> $this->userType,'f' => 'login']))->redirect();
        }
        (new Route('home'))->redirect();
    }

    public function user(){
        $data = [
            'id' => $this->id,
            'username' => $this->username,
            'name' => $this->name,
            'userType' => $this->userType
        ];
        if($this->userType == 'receiver'){
            $data['blood_group'] = $this->blood_group;
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
        (new Route('home'))->redirect();
    }
}