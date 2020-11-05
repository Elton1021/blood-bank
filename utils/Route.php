<?php

class Route {
    //Route is very important to get url strings or to redirect
    //mainRoute is be empty if the main dierctory is htdocs itself
    //if not then add the path from htdocs to index.php
    //e.g. my code is in ./htdocs/blood-bank-server so the string is '/blood-bank-server'
    private $mainRoute = '';

    //routes to keep errors to minimum
    private $routes = [
        'home' => '/view/home.php',
        'auth' => '/view/auth.php',
        'bloodSamples' => '/view/blood-samples.php',
        'addSamples' => '/view/add-samples.php',
        'storeSample' => '/view/process/store-sample.php',
        'requestBlood' => '/view/process/request-blood.php',
        'viewRequest' => '/view/view-request.php',
        'logout' => '/view/process/logout.php',
        'login' => '/view/process/login.php',
        'register' => '/view/process/register.php',
    ];

    private $key;
    private $queries = [];

    public function __construct($key, $queries = array()){
        $this->key = $key;
        $this->queries = $queries;
    }

    //redirect to any page available in routes
    public function redirect(){
        $route = $this->get();
        if(!empty($route)){
            header('Location: '.$route);
            exit();
        }
    }

    //will return exact url based on key from the routes array
    public function get(){
        if(!isset($this->key) && !isset($this->routes[$this->key])){
            return;
        }
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
            $route = "https://";   
        else  
            $route = "http://";   
        $route.= $_SERVER['HTTP_HOST'].($this->mainRoute ?? '').$this->routes[$this->key];

        if(isset($this->queries) && sizeof($this->queries) > 0){
            $params = [];
            foreach($this->queries as $param => $value){
                $params []= $param.'='.$value;
            }
            $route .= '?'.implode('&',$params);
        }

        return $route;
    }
}