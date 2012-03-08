<?php

class Log
{
    private $requests = array();
    private $requests_params = array();
    
    public function log($str = '', $values = array()) {
        if(!empty($str)) {
            $this->addRequest($str);
            $this->addParams($values);
            $this->logWrite($str, $values);
        }
    }
    
    private function addRequest($request) {
        $this->requests[] = $request;
    }

    private function addParams($par) {
        $this->requests_params[] = $par;
    }
    
    public function getRequests() {
        return $this->requests;
    }

     public function getQueryParams() {
        return $this->requests_params;
    }

    public function logWrite($str, $params) {
        $path = FWPATH .'project/tmp/log/log.txt';
        $str = str_replace('?', '%s', $str);
        array_unshift($params, $str);
        // Если не передано параметров, но в тексте попадутся похожие на символы замены элементы
        if(count($params) > 1) {
            $query = ">>> ".call_user_func_array("sprintf", $params)."\n";
        } else {
            $query = ">>> ".$str."\n";
        }
        $fp = fopen($path, 'a+');
        flock($fp, LOCK_EX);
        fwrite($fp, $query);
        flock($fp, LOCK_UN);
        fclose($fp);
    }
}