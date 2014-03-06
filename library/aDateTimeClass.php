<?php

class aDateTimeClass {
    const plusStampAsiaBangkok = 25200; //(60*60*7); //GMT+7
    const plusStampSouthAfrica = 7200; //(60*60*2); //GMT+2

    var $gmtDay,$gmtMonth,$gmtYear,$gmtHour,$gmtMin,$gmtSec;

    function setGmtDay($value){
        $this->gmtDay = $value;
    }
    function setGmtMonth($value){
        $this->gmtMonth = $value;
    }
    function setGmtYear($value){
        $this->gmtYear = $value;
    }
    function setGmtHour($value){
        $this->gmtHour = $value;
    }
    function setGmtMin($value){
        $this->gmtMin = $value;
    }
    function setGmtSec($value){
        $this->gmtSec = $value;
    }
    
    function getGmtTimeStamp(){
        return mktime($this->gmtHour,$this->gmtMin,$this->gmtSec,$this->gmtMonth,$this->gmtDay,$this->gmtYear);
    }

}
?>
