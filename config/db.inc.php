<?php
    $host = "localhost";
    $user = "root";
    $passwd = "";
    $dbname = "worldcup2010";

    mysql_connect($host,$user,$passwd) or die("DB Connection Fail");
//    mysql_query("SET NAMES TIS620");
    mysql_query("SET NAMES UTF8");
    mysql_select_db($dbname) or die("Select DB Fail");
?>
