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

    public function validateUserType($userType = 'hospital'){
        if(!isset($this->auth->user()['id']) || isset($this->auth->user()['id']) && isset($this->auth->user()['userType']) && $this->auth->user()['userType'] != $userType){
            (new Route('home'))->redirect();
        }
    }

    public function index(){
        $columns = [];
        $sql = '';
        if(isset($this->auth->user()['id']) && isset($this->auth->user()['userType']) && $this->auth->user()['userType'] == 'hospital'){
            $sql = "SELECT
                    hd.name AS hospital,
                    sd.blood_group
                FROM
                    sample_details sd
                INNER JOIN
                    hospital_details hd
                ON
                    hd.id = sd.hospital_id
                WHERE
                    sd.status = 'A'
                ORDER BY sd.updated_on DESC";
            $columns = ['hospital','blood_group'];
        }else if(isset($this->auth->user()['id']) && isset($this->auth->user()['userType']) && $this->auth->user()['userType'] == 'receiver'){
            $sql = "SELECT 
                (CASE
                    WHEN sd.blood_group != '".$this->auth->user()['blood_group']."' THEN '<button class=\"btn btn-primary\" disabled>Request</button>'
                    WHEN sr.id IS NOT NULL THEN '<button class=\"btn btn-secondary\" disabled>Requested</button>'
                    ELSE CONCAT('<button class=\"btn btn-primary\" onclick=\"requestBlood(event)\" sample-id=\"',sd.id,'\">Request</button>')
                END) AS activity,
                hd.name AS hospital,
                sd.blood_group
            FROM
                sample_details sd
            INNER JOIN
                hospital_details hd
            ON
                hd.id = sd.hospital_id
            LEFT JOIN
                sample_request sr
            ON
                sd.id = sr.sample_id AND sr.receiver_id = 8
            WHERE
                sd.status = 'A'
            ORDER BY sd.updated_on DESC";
            $columns = ['activity','hospital','blood_group'];
        }else{
            $sql = "SELECT
                    '<a class=\"btn btn-primary\" href=\"".(new Route('auth',['t' => 'receiver', 'f' => 'login']))->get()."\">Request</a>' AS activity,
                    hd.name AS hospital,
                    sd.blood_group
                FROM
                    sample_details sd
                INNER JOIN
                    hospital_details hd
                ON
                    hd.id = sd.hospital_id
                WHERE
                    sd.status = 'A'
                ORDER BY sd.updated_on DESC";
            $columns = ['activity','hospital','blood_group'];
        }
        return $this->getDatatableData($columns, $sql);
    }

    public function getDatatableData($columns, $sql){
        try{
            return [$columns,$this->getByQuery($sql)];
        } catch(Throwable | Error | Exception $e) {
            $this->log->error($e);
        }
        return [$columns,[]];
    }

    public function requestBlood(){
        $this->validateUserType('receiver');
        try{
            $data = $this->getData(['where' => [
                ['id' => $_POST['sampleId']],
            ]]);
            if(sizeof($data) > 0 && isset($data[0]['blood_group']) && $data[0]['blood_group'] == $this->auth->user()['blood_group']){
                $res = $this->insert([
                    'sample_id' => $_POST['sampleId'],
                    'receiver_id' => $this->auth->user()['id'],
                ], 'sample_request');
                if($res)
                    return json_encode(['status' => 200, 'data' => true ]);
            }
        } catch(Throwable | Error | Exception $e) {
            $this->log->error($e);
        }
        return json_encode(['status' => 500, 'data' => false ]);
    }

    public function getByHospital(){
        $this->validateUserType();
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

    public function getRequests(){
        $this->validateUserType();
        $sql = 'SELECT
                rd.name AS receiver_name,
                sd.blood_group
            FROM
                sample_request sr
            INNER JOIN
                sample_details sd
            ON
                sr.sample_id = sd.id
            INNER JOIN
                hospital_details hd
            ON
                hd.id = sd.hospital_id
            INNER JOIN
                receiver_details rd
            ON
                rd.id = sr.receiver_id
            WHERE
                hd.id = '.$this->auth->user()['id'].'
            ORDER BY sr.added_on DESC';
        $columns = ['receiver_name','blood_group'];
        return $this->getDatatableData($columns, $sql);
    }

    public function store(){
        $this->validateUserType();
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