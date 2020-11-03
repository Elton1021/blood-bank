<?php

class Log {
    private function write($filename,$data){
        global $BACKTRACK;
        $file = fopen(($BACKTRACK ?? '../../').$filename.".txt", "a+");
        fwrite($file, $data);
        fclose($file);
    }

    public function error($e){
        $data = PHP_EOL.PHP_EOL .'['.date('Y-m-d H:i:s').']'. PHP_EOL .'Message: '.$e->getMessage(). PHP_EOL .'File: '.$e->getFile(). PHP_EOL .'Line: '.$e->getLine();
        $this->write('error',$data);
    }
}