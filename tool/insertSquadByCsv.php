<?php
require_once("../config/db.inc.php");

//$teamID = 1;
//$objCSV = fopen("RSA.csv", "r");
//$teamID = 2;
//$objCSV = fopen("MEX.csv", "r");
//$teamID = 3;
//$objCSV = fopen("URU.csv", "r");
//$teamID = 4;
//$objCSV = fopen("FRA.csv", "r");
//$teamID = 5;
//$objCSV = fopen("ARG.csv", "r");
//$teamID = 6;
//$objCSV = fopen("NGA.csv", "r");
//$teamID = 7;
//$objCSV = fopen("KOR.csv", "r");
//$teamID = 8;
//$objCSV = fopen("GRE.csv", "r");
//$teamID = 9;
//$objCSV = fopen("ENG.csv", "r");
//$teamID = 10;
//$objCSV = fopen("USA.csv", "r");
//$teamID = 11;
//$objCSV = fopen("ALG.csv", "r");
//$teamID = 12;
//$objCSV = fopen("SVN.csv", "r");
//$teamID = 13;
//$objCSV = fopen("GER.csv", "r");
//$teamID = 14;
//$objCSV = fopen("AUS.csv", "r");
//$teamID = 15;
//$objCSV = fopen("SRB.csv", "r");
//$teamID = 16;
//$objCSV = fopen("GHA.csv", "r");
//$teamID = 17;
//$objCSV = fopen("NED.csv", "r");
//$teamID = 18;
//$objCSV = fopen("DEN.csv", "r");
//$teamID = 19;
//$objCSV = fopen("JPN.csv", "r");
//$teamID = 20;
//$objCSV = fopen("CMR.csv", "r");
//$teamID = 21;
//$objCSV = fopen("ITA.csv", "r");
//$teamID = 22;
//$objCSV = fopen("PAR.csv", "r");
//$teamID = 23;
//$objCSV = fopen("NZL.csv", "r");
//$teamID = 24;
//$objCSV = fopen("SVK.csv", "r");
//$teamID = 25;
//$objCSV = fopen("BRA.csv", "r");
//$teamID = 26;
//$objCSV = fopen("PRK.csv", "r");
//$teamID = 27;
//$objCSV = fopen("CIV.csv", "r");
//$teamID = 28;
//$objCSV = fopen("POR.csv", "r");
//$teamID = 29;
//$objCSV = fopen("ESP.csv", "r");
//$teamID = 30;
//$objCSV = fopen("SUI.csv", "r");
//$teamID = 31;
//$objCSV = fopen("HON.csv", "r");
//$teamID = 32;
//$objCSV = fopen("CHI.csv", "r");

$no = 1;
while (($objArr = fgetcsv($objCSV, 1000, ",")) !== FALSE) {
//    echo $no;
//    echo "<br>";
//    echo "0-".$objArr[0];
//    echo "<br>";
//    echo "1-".ucwords(strtolower($objArr[1]));
//    echo "<br>";
//    echo "2-".$objArr[2];
//    echo "<br>";
//    echo "3-".$objArr[3];
//    echo "<br>";
//    echo "4-".$objArr[4];
//    echo "<br>";
//    echo "5-".$objArr[5];
//    echo "<hr>";
    
    $sql = "Insert Into wc2010_player(";
    $sql .= "full_name";
    $sql .= ", birth_date";
    $sql .= ", height";
    $sql .= ", playing_position";
    $sql .= ", club_entirely";
    $sql .= ", national_team";
    $sql .= ", national_team_number";
    $sql .= ") Values (";
    $sql .= " '".ucwords(strtolower($objArr[1]))."'";
    $sql .= ", STR_TO_DATE('".$objArr[2]."', '%e/%c/%Y')";
    $sql .= ", ".$objArr[5];
    $sql .= ", '".$objArr[3]."'";
    $sql .= ", '".$objArr[4]."'";
    $sql .= ", ".$teamID;
    $sql .= ", ".$objArr[0];
    $sql .= ")";

//    echo $sql;
//    echo "<hr>";

    $result = mysql_query($sql) or die("Query failed 5");

    echo $no."-".$objArr[1];
    if ($result) {
        echo "-OK";
    } else {
        echo "-FAIL";
    }
    echo "<br>";
    $no++;
}
mysql_close();
?>
