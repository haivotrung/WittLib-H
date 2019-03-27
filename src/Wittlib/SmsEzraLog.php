<?php

namespace Wittlib;

use \atk4\dsql\Query;

class SmsEzraLog {
    public function __construct () {
        $this->c = \atk4\dsql\Connection::connect(DSN,USER,PASS);
        $this->invalidArgs = array();
        $this->paramsOk = false;
        $this->timestamp = date ('Y-m-d H:i:s');
        $this->smsBody = '';
    }

    public function setParams($request) {
        $required = array ('title','number','item');
        foreach ($required as $param) {
            if (array_key_exists($param,$request)) {
                $this->$param = trim($request[$param]);
            } 
            else { array_push($this->invalidArgs, 'Missing required parameter: '.$param); }
        }
        if (isset($this->number)) { $this->validateNumber(); }

        if (sizeof($this->invalidArgs) == 0) {
            $this->paramsOk = true;
            $this->prepSmsBody();
        
        }

        /*        
        if (! array_key_exists('auto',$conf) || $conf['auto'] == true) {
            //unless conf[auto] is false, log the stats right away
            $this->logBookInfo();
            $this->updateSmsStats();
        }
        */
    }
    
    private function validateNumber() {
        $num = $this->number;
        // remove all non-digits from phone 
        $num = preg_replace("/[^\d]/", "", $num); 
        if (strlen($num) == 10) {
            $this->toNumber = '+1' . $num;
        }
        else {
            array_push($this->invalidArgs,'Ten-digit phone number required');
            $this->jsReturn = "alert('Ten-digit phone number required');";
        }
    }

    private function prepSmsBody () {
        $body = $this->item . PHP_EOL .'Title: '.$this->title;
        $body = preg_replace ("/\(\s+/", "(", $body);
        $body = stripslashes($body);
        $this->smsBody = $body;
    }

    public function logBookInfo() {
        
    }
    
    public function updateSmsStats() {
        
    }

}