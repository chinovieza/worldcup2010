<?php
function getTeamName($id) {
    $sql = "Select name From wc2010_national_team Where id = ".$id;
    $result = mysql_query($sql) or die("Query failed");
    $rec = mysql_fetch_array($result);
    $data = $rec[0];
    mysql_free_result($result);

    return $data;
}

function getStadiumName($id) {
    $sql = "Select name From wc2010_stadium Where id = ".$id;
    $result = mysql_query($sql) or die("Query failed");
    $rec = mysql_fetch_array($result);
    $data = $rec[0];
    mysql_free_result($result);

    return $data;
}

function age($bMonth,$bDay,$bYear) {

    $cMonth = date('n');
    $cDay = date('j');
    $cYear = date('Y');

    if(($cMonth >= $bMonth && $cDay >= $bDay) || ($cMonth > $bMonth)) {
        return ($cYear - $bYear);
    } else {
        return ($cYear - $bYear - 1);
    }
}
?>
