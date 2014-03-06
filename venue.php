<?php
require_once("./config/db.inc.php");
require_once("./config/date.inc.php");
require_once("./library/utilityFunction.php");
require_once("./library/tournamentClass.php");

$getVenue = isset($_GET["venue"]) ? $_GET["venue"] : 1;
$getTz = isset($_GET["tz"]) ? $_GET["tz"] : 1;

if($getTz==2) {
   $tZone = "SA";
} else if ($getTz==3) {
   $tZone = "GMT";
} else {
   $tZone = "TH";
}

$sql = "Select name, city, capacity, coordinates, built, construction, completion";
$sql .= " From wc2010_stadium";
$sql .= " Where id = ".$getVenue;
$result = mysql_query($sql) or die("Query failed");
$rec = mysql_fetch_array($result, MYSQL_ASSOC);
$stadiumName = $rec["name"];
$stadiumCity = $rec["city"];
$stadiumCapacity = number_format($rec["capacity"]);
$stadiumCoordinates = $rec["coordinates"];
$stadiumBuilt = $rec["built"];
$stadiumConstruction = $rec["construction"];
$stadiumCompletion = $rec["completion"];
mysql_free_result($result);
?>
<div class="content-box"><!-- Start Content Box -->

    <div class="content-box-content">
        <?
        echo "<h2>".$stadiumName." - ".$stadiumCity."</h2>";
        echo "<img src=\"./images/stadium/".$getVenue."a.jpg\">";
        echo "<br><br>";
        echo "<h3>Facts</h3>";
        echo "<b>Name : </b>".$stadiumName;
        echo "<br><br>";
        echo "<b>City : </b>".$stadiumCity;
        echo "<br><br>";
        if ($stadiumBuilt!="") {
            echo "<b>Built : </b>".$stadiumBuilt;
            echo "<br><br>";
        }
        echo "<b>Construction : </b>".$stadiumConstruction;
        echo "<br><br>";
        echo "<b>Completion : </b>".$stadiumCompletion;
        echo "<br><br>";
        echo "<b>Capacity : </b>".$stadiumCapacity;
        //echo "<b>Coordinates : </b>".$stadiumCoordinates;
        ?>
    </div>
</div> <!-- End .content-box -->

<div class="content-box"><!-- Start Content Box -->

    <div class="content-box-header">

        <h3>MATCHES</h3>
        <div class="clear"></div>

    </div> <!-- End .content-box-header -->

    <div class="content-box-content">

<?
$sql = "SELECT A.id, team_home, team_away, stage, remark_home, remark_away, match_status";
$sql .= " , B.name AS hteam, B.short_name AS hteams, C.name AS ateam, C.short_name AS ateams, B.group_stage AS mgroup";
$sql .= " , D.name AS stage_name";
$sql .= " , DATE_FORMAT(match_datetime_gmt, '%e') AS mday, DATE_FORMAT(match_datetime_gmt, '%c') AS mmonth, DATE_FORMAT(match_datetime_gmt, '%Y') AS myear";
$sql .= " , DATE_FORMAT(match_datetime_gmt, '%H') AS mhour, DATE_FORMAT(match_datetime_gmt, '%i') AS mmin, DATE_FORMAT(match_datetime_gmt, '%s') AS msec";
$sql .= " FROM wc2010_match A";
$sql .= " LEFT JOIN wc2010_national_team B";
$sql .= " ON A.team_home = B.id";
$sql .= " LEFT JOIN wc2010_national_team C";
$sql .= " ON A.team_away = C.id";
$sql .= " INNER JOIN wc2010_stage D";
$sql .= " ON A.stage = D.id";
$sql .= " WHERE A.stadium = ".$getVenue;
$sql .= " ORDER BY match_datetime_gmt ASC";

$result = mysql_query($sql) or die("Query failed");
?>
            <table>

                <thead>
                    <tr>
                        <th>Match</th>
                        <th>Date-Time [<?echo $tZone;?>]</th>
                        <th>Stage</th>
                        <th></th>
                        <th>Result</th>
                        <th></th>
                    </tr>

                </thead>

                <tfoot>
                    <tr>
                        <td colspan="6">
                            <div class="bulk-actions align-left">
                                Timezone :
                                <a href="<?echo $_SERVER["PHP_SELF"]."?show=venue&venue=".$getVenue."&tz=1";?>">THAILAND</a>
                                |
                                <a href="<?echo $_SERVER["PHP_SELF"]."?show=venue&venue=".$getVenue."&tz=2";?>">SOUTH AFRICA</a>
                                |
                                <a href="<?echo $_SERVER["PHP_SELF"]."?show=venue&venue=".$getVenue."&tz=3";?>">GMT</a>
                            </div>
                            <div class="clear"></div>
                        </td>
                    </tr>
                </tfoot>

                <tbody>
<?

$no = 1;
while ($rec = mysql_fetch_array($result, MYSQL_ASSOC)) {
    if ($rec["team_home"]!="") {
        $sideA = "<a href=\"".$_SERVER["PHP_SELF"]."?show=team&team=".$rec["team_home"]."\"><img src=\"images/team/flag/19/".strtolower($rec["hteams"]).".gif\">&nbsp;&nbsp;".$rec["hteam"]."</a>";
    } else {
        $sideA = $rec["remark_home"];
    }
    if ($rec["team_away"]!="") {
        $sideB = "<a href=\"".$_SERVER["PHP_SELF"]."?show=team&team=".$rec["team_away"]."\"><img src=\"images/team/flag/19/".strtolower($rec["ateams"]).".gif\">&nbsp;&nbsp;".$rec["ateam"]."</a>";
    } else {
        $sideB = $rec["remark_away"];
    }
    if ($rec["stage"]==1) {
        $roundStage = "<a href=\"".$_SERVER["PHP_SELF"]."?show=group&group=".$rec["mgroup"]."\">Group ".$rec["mgroup"]."</a>";
    } else {
        $roundStage = "<a href=\"".$_SERVER["PHP_SELF"]."?show=stage&stage=".$rec["stage"]."\">".$rec["stage_name"]."</a>";
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
    $matchTime = date("d",$procStmp)."/".date("m",$procStmp)."/".date("Y",$procStmp)." ".date("H",$procStmp).":".date("i",$procStmp);

    if ($rec["match_status"]==4) {
        $tnmObj1 = new tournamentClass();
        $tnmObj1 -> setTeamID($rec["team_home"]);
        $tnmObj1 -> setMatchID($rec["id"]);
        $teamHomeScore = $tnmObj1 -> getScore();
        $teamAwayScore = $tnmObj1 -> getCompetitorScore();

        $matchDetail = "<a href=\"".$_SERVER["PHP_SELF"]."?show=match&match=".$rec["id"]."\"><b>".$teamHomeScore." - ".$teamAwayScore."</b></a>";
    } else {
        $matchDetail = "<a href=\"".$_SERVER["PHP_SELF"]."?show=match&match=".$rec["id"]."\">V</a>";
    }

?>
                    <tr>
                        <td><?echo $no;?></td>
                        <td><?echo $matchTime;?></td>
                        <td><?echo $roundStage;?></td>
                        <td><?echo $sideA;?></td>
                        <td><?echo $matchDetail;?></td>
                        <td><?echo $sideB;?></td>
                    </tr>
<?
    $no++;
}
mysql_free_result($result);
?>

                </tbody>

            </table>

        <div class="tab-content" id="tab2">



        </div> <!-- End #tab2 -->

    </div> <!-- End .content-box-content -->

</div> <!-- End .content-box -->
<?
mysql_close();
?>
