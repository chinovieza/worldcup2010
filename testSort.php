<?php
    //$a = array(1,7,3,2,4,8,9,5);
    //$a = array("SPN"=>1,"JPY"=>7,"RSU"=>3,"DDT"=>2,"DAY"=>4,"ENG"=>8,"BRA"=>9,"GER"=>5);
    $a = array("SPN"=>array(2,3),"APN"=>array(1,3),"BPN"=>array(2,5),"JPY"=>array(2,4),"RSU"=>array(3,1),"DDT"=>array(1,1));
    print_r($a);
    //sort($a);
    //asort($a);
    arsort($a);
    print_r($a);
?>
