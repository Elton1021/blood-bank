<?php
class Auth {

    private $id;
    private $username;
    private $name;
    private $blood_group;
    private $userType;

    public function __construct($userData = null){
        if(isset($userData) && isset($userData['name']) && isset($userData['username']) && isset($userData['id']) && isset($userData['userType']) && ($userData['userType'] == "receiver" && $userData['blood_group'] || $userData['userType'] == "hospital")){
            setcookie('a_id',$userData['id'],time() + (24 * 60 * 60),'/');
            setcookie('a_name',$userData['name'],time() + (24 * 60 * 60),'/');
            setcookie('a_username',$userData['username'],time() + (24 * 60 * 60),'/');
            setcookie('a_userType',$userData['userType'],time() + (24 * 60 * 60),'/');
            $this->id = $userData['id'];
            $this->name = $userData['name'];
            $this->username = $userData['username'];
            $this->userType = $userData['userType'];
            if($userData['userType'] == "receiver"){
                $this->blood_group = $userData['blood_group'];
                setcookie('a_blood_group',$userData['blood_group'],time() + (24 * 60 * 60),'/');
            }
        } else if(isset($_COOKIE) && isset($_COOKIE['a_name']) && isset($_COOKIE['a_username']) && isset($_COOKIE['a_id']) && isset($_COOKIE['a_userType']) && ($_COOKIE['a_userType'] == "receiver" && $_COOKIE['a_blood_group'] || $_COOKIE['a_userType'] == "hospital")) {
            $this->id = $_COOKIE['a_id'];
            $this->name = $_COOKIE['a_name'];
            $this->username = $_COOKIE['a_username'];
            $this->userType = $_COOKIE['a_userType'];
            if($this->userType == "receiver"){
                $this->blood_group = $_COOKIE['a_blood_group'];
            }
        }
    }

    public function user(){
        $data = [
            'id' => $this->id,
            'username' => $this->username,
            'name' => $this->name, 'userType' => $this->userType
        ];
        if($this->userType == 'receiver'){
            $data []= $this->blood_group;
        }
        return $data;
    }

    public function destroy(){
        setcookie('a_id',"",time() - 3600,'/');
        setcookie('a_name',"",time() - 3600,'/');
        setcookie('a_username',"",time() - 3600,'/');
        setcookie('a_userType',"",time() - 3600,'/');
        setcookie('a_blood_group',"",time() - 3600,'/');
        $this->id = null;
        $this->name = null;
        $this->username = null;
        $this->userType = null;
    }
}