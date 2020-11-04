<?php
$BACKTRACK = $BACKTRACK ?? "../../";
require_once($BACKTRACK.'controllers/AuthenticateController.php');
require_once($BACKTRACK."utils/Log.php");
require_once($BACKTRACK."utils/MysqliUtil.php");
require_once($BACKTRACK."utils/Route.php");

class SampleDetailsController extends MysqliUtil{

    public function __construct(){
        MysqliUtil::__construct();
        $this->tableName = "sample_details";
        $this->log = new Log;
        $this->auth = new AuthenticateController;
    }

    public function __destruct(){
        MysqliUtil::__destruct();
    }

    public function validateHospital(){
        if(!isset($this->auth->user()['id']) && isset($this->auth->user()['id']) && isset($this->auth->user()['userType']) && $this->auth->user()['userType'] == 'receiver'){
            (new Route('home'))->redirect();
        }
    }

    public function index(){
        $columns = [];
        $sql = '';
        if(isset($this->auth->user()['id']) && isset($this->auth->user()['userType']) == 'hospital'){
            $sql = "SELECT hd.name AS hospital, sd.blood_group FROM sample_details sd INNER JOIN hospital_details hd WHERE sd.status = 'A'";
            $columns = ['hospital','blood_group'];
        }else if(isset($this->auth->user()['id']) && isset($this->auth->user()['userType']) == 'receiver'){
            //fix it later
            $sql = "SELECT hd.name AS hospital, sd.blood_group FROM sample_details sd INNER JOIN hospital_details hd WHERE sd.status = 'A'";
            $columns = ['hospital','blood_group'];
        }else{
            $sql = "SELECT '<a class=\"btn btn-primary\" href=\"".(new Route('auth',['t' => 'receiver', 'f' => 'login']))->get()."\">Request</a>' AS activity, hd.name AS hospital, sd.blood_group FROM sample_details sd INNER JOIN hospital_details hd WHERE sd.status = 'A'";
            $columns = ['activity','hospital','blood_group'];
        }

        try{
            return [$columns,$this->getByQuery($sql)];
        } catch(Throwable | Error | Exception $e) {
            $this->log->error($e);
        }
        return [$columns,[]];
    }

    public function getByHospital(){
        $this->validateHospital();
        try{
            $res = $this->getData(['where' => [
                ['hospital_id' => $this->auth->user()['id']]
            ]]);
            $data = [];
            foreach($res as $r){
                $data[$r['blood_group']] = $r['status'] == 'A' ? 'checked' : null;
            }
            return $data;
        } catch(Throwable | Error | Exception $e) {
            $this->log->error($e);
        }
        return [];
    }

    public function store(){
        $this->validateHospital();
        try{
            if(isset($_POST['blood_group']) && isset($_POST['status'])){
                $data = $this->getData([ 'fields' => ['id'] ,'where' => [
                    ['blood_group' => $_POST['blood_group']],
                    ['hospital_id' => $this->auth->user()['id']],
                ]]);
                $res = false;
                if(sizeof($data) > 0){
                    $res = $this->update([
                        'blood_group' => $_POST['blood_group'],
                        'status' => $_POST['status'],
                    ],['id' => $data[0]['id']]);
                }else{
                    $res = $this->insert([
                        'blood_group' => $_POST['blood_group'],
                        'status' => $_POST['status'],
                        'hospital_id' => $this->auth->user()['id'],
                    ]);
                }
                if($res){
                    return json_encode(['status' => 200, 'data' => true]);
                }
            }
            throw new Exception('blood_group and/or status is not defined');
        } catch(Throwable | Error | Exception $e) {
            $this->log->error($e);
        }
        return json_encode(['status' => 500, 'data' => false ]);
    }
}