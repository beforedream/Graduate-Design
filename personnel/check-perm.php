<?php
class caps{
    var $EID = -1;
    var $lastTime = 0;
    var $timeOut = 300;
    var $pageName;
    public function __construct($new_EID, $new_pageName){
        $this->EID = $new_EID;
        $this->pageName = $new_pageName;
        /**
         * TODO
         * get some info from database, and fill other parameter;
         * if not exist echo login page;
         */
    }
    /**
     * send login page
     */
    public function sendPage(){

    }

    public function updateCap(){

    }
    /**
     * if exist return its' perm;
     * else return 0;
     * 0-none, 1-read, 2-write, 3-all;
     */
    private function checkPerm(){

    }
}
$cap = new caps();
?>