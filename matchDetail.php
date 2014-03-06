<?php
require_once("./config/db.inc.php");
require_once("./config/date.inc.php");

$getMatch = isset($_GET["match"]) ? $_GET["match"] : "";
$getTz = isset($_GET["tz"]) ? $_GET["tz"] : 1;

if($getTz==2) {
   $tZone = "SA";
} else if ($getTz==3) {
   $tZone = "GMT";
} else {
   $tZone = "TH";
}

$sql = "SELECT A.id, team_home, team_away, stadium, remark_home, remark_away, match_status";
$sql .= " , B.name AS hteam, B.short_name AS hteams, C.name AS ateam, C.short_name AS ateams, B.group_stage AS mgroup";
$sql .= " , D.name AS venue_name, D.city AS venue_city";
$sql .= " , DATE_FORMAT(match_datetime_gmt, '%e') AS mday, DATE_FORMAT(match_datetime_gmt, '%c') AS mmonth, DATE_FORMAT(match_datetime_gmt, '%Y') AS myear";
$sql .= " , DATE_FORMAT(match_datetime_gmt, '%H') AS mhour, DATE_FORMAT(match_datetime_gmt, '%i') AS mmin, DATE_FORMAT(match_datetime_gmt, '%s') AS msec";
$sql .= " FROM wc2010_match A";
$sql .= " LEFT JOIN wc2010_national_team B";
$sql .= " ON A.team_home = B.id";
$sql .= " LEFT JOIN wc2010_national_team C";
$sql .= " ON A.team_away = C.id";
$sql .= " INNER JOIN wc2010_stadium D";
$sql .= " ON A.stadium = D.id";
$sql .= " WHERE A.id = ".$getMatch;

$result = mysql_query($sql) or die("Query failed");
$rec = mysql_fetch_array($result, MYSQL_ASSOC);

if ($rec["team_home"]!="") {
    $sideA = "<a href=\"".$_SERVER["PHP_SELF"]."?show=team&team=".$rec["team_home"]."\"><img src=\"images/team/flag/47/".strtolower($rec["hteams"]).".gif\">&nbsp;&nbsp;".$rec["hteam"]."</a>";
} else {
    $sideA = $rec["remark_home"];
}
if ($rec["team_away"]!="") {
    $sideB = "<a href=\"".$_SERVER["PHP_SELF"]."?show=team&team=".$rec["team_away"]."\">".$rec["ateam"]."&nbsp;&nbsp;<img src=\"images/team/flag/47/".strtolower($rec["ateams"]).".gif\"></a>";
} else {
    $sideB = $rec["remark_away"];
}
$mGmtStmp = mktime ($rec["mhour"], $rec["mmin"], $rec["msec"], $rec["mmonth"], $rec["mday"], $rec["myear"]);
$mThStmp = $mGmtStmp + $plusStampAsiaBangkok;
$mSaStmp = $mGmtStmp + $plusStampSouthAfrica;
if($getTz==2) {
    $procStmp = $mSaStmp;
} else if ($getTz==3) {
    $procStmp = $mGmtStmp;
} else {
    $procStmp = $mThStmp;
}
$matchTime = strtolower($weekDayShortEN[date("w",$procStmp)]).", ".date("d",$procStmp)." ".$monthEN[(date("m",$procStmp)-1)]." ".date("Y",$procStmp).", ".date("H",$procStmp).":".date("i",$procStmp)." [".$tZone."]";
mysql_free_result($result);
?>
<div class="content-box"><!-- Start Content Box -->

    <div class="content-box-content">
        <table id="chino">
            <tbody>
                <tr><td></td><td><div align="center"><b><?echo $matchTime;?></b></div></td><td></td></tr>
                <tr>
                    <?
                    echo "<td><div align=\"left\" valign=\"top\"><h2>".$sideA."</h2><!--aaaaa(82)<br>cccc(7)<br>dddd(3)--></div></td>";
                    echo "<td><div align=\"center\" valign=\"top\"><h2> - </h2><br></div></td>";
                    echo "<td><div align=\"right\" valign=\"top\"><h2>".$sideB."</h2></div></td>";
                    ?>
                </tr>
            </tbody>
        </table>
        <!--
        <div>
            <div align="center">aaaa</div>
            <div align="left">bbbbbbbbbb</div>
            <div align="right">cc</div>
        </div>
        -->
    </div>
</div> <!-- End .content-box -->
<?
mysql_close();
?>
