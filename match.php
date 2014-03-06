<?php
require_once("./config/db.inc.php");
require_once("./config/date.inc.php");
require_once("./library/tournamentClass.php");

//$defaultTab = 1;
$defaultTab = 2;

$getTz = isset($_GET["tz"]) ? $_GET["tz"] : 1;
$getTab = isset($_GET["tab"]) ? $_GET["tab"] : $defaultTab;

if($getTz==2) {
   $tZone = "SA";
} else if ($getTz==3) {
   $tZone = "GMT";
} else {
   $tZone = "TH";
}
?>

<div class="content-box"><!-- Start Content Box -->

    <div class="content-box-header">

        <h3>MATCHES</h3>

        <ul class="content-box-tabs">
<?
    $ulTab1Cls = "";
    $ulTab2Cls = "";
    if ($getTab==1) {
        $ulTab1Cls = "class=\"default-tab\"";
    } else if ($getTab==2) {
        $ulTab2Cls = "class=\"default-tab\"";
    }
?>
            <li><a href="#tab1" <?echo $ulTab1Cls;?>>Group Stage</a></li> <!-- href must be unique and match the id of target div -->
            <li><a href="#tab2" <?echo $ulTab2Cls;?>>Knockout Stage</a></li>
        </ul>

        <div class="clear"></div>

    </div> <!-- End .content-box-header -->

    <div class="content-box-content">

<?
    if ($getTab==1) {
        $tab1Cls = "tab-content default-tab";
    } else {
        $tab1Cls = "tab-content";
    }
?>
        <div class="<?echo $tab1Cls;?>" id="tab1"> <!-- This is the target div. id must match the href of this div's tab -->

<?
$sql = "SELECT A.id, team_home, team_away, stadium, match_status";
$sql .= " , B.name AS hteam, B.short_name AS hteams, C.name AS ateam, C.short_name AS ateams, B.group_stage AS mgroup";
$sql .= " , D.name AS venue_name, D.city AS venue_city";
$sql .= " , DATE_FORMAT(match_datetime_gmt, '%e') AS mday, DATE_FORMAT(match_datetime_gmt, '%c') AS mmonth, DATE_FORMAT(match_datetime_gmt, '%Y') AS myear";
$sql .= " , DATE_FORMAT(match_datetime_gmt, '%H') AS mhour, DATE_FORMAT(match_datetime_gmt, '%i') AS mmin, DATE_FORMAT(match_datetime_gmt, '%s') AS msec";
$sql .= " FROM wc2010_match A";
$sql .= " INNER JOIN wc2010_national_team B";
$sql .= " ON A.team_home = B.id";
$sql .= " INNER JOIN wc2010_national_team C";
$sql .= " ON A.team_away = C.id";
$sql .= " INNER JOIN wc2010_stadium D";
$sql .= " ON A.stadium = D.id";
$sql .= " WHERE stage = 1";
$sql .= " ORDER BY match_datetime_gmt ASC";

$result = mysql_query($sql) or die("Query failed");
?>
            <table>

                <thead>
                    <tr>
                        <th>Match</th>
                        <th>Date-Time [<?echo $tZone;?>]</th>
                        <th>Group</th>
                        <th></th>
                        <th>Result</th>
                        <th></th>
                        <th>Venue</th>
                    </tr>

                </thead>

                <tfoot>
                    <tr>
                        <td colspan="7">
                            <div class="bulk-actions align-left">
                                Timezone :
                                <a href="<?echo $_SERVER["PHP_SELF"]."?show=match&tab=".$getTab."&tz=1";?>">THAILAND</a>
                                |
                                <a href="<?echo $_SERVER["PHP_SELF"]."?show=match&tab=".$getTab."&tz=2";?>">SOUTH AFRICA</a>
                                |
                                <a href="<?echo $_SERVER["PHP_SELF"]."?show=match&tab=".$getTab."&tz=3";?>">GMT</a>
                            </div>
                            <div class="clear"></div>
                        </td>
                    </tr>
                </tfoot>

                <tbody>
<?

$no = 1;
while ($rec = mysql_fetch_array($result, MYSQL_ASSOC)) {
    $sideA = "<a href=\"".$_SERVER["PHP_SELF"]."?show=team&team=".$rec["team_home"]."\"><img src=\"images/team/flag/19/".strtolower($rec["hteams"]).".gif\">&nbsp;&nbsp;".$rec["hteam"]."</a>";
    $sideB = "<a href=\"".$_SERVER["PHP_SELF"]."?show=team&team=".$rec["team_away"]."\"><img src=\"images/team/flag/19/".strtolower($rec["ateams"]).".gif\">&nbsp;&nbsp;".$rec["ateam"]."</a>";
    $venue = "<a href=\"".$_SERVER["PHP_SELF"]."?show=venue&venue=".$rec["stadium"]."\">".$rec["venue_name"]."</a>";
    $matchGroup = "<a href=\"".$_SERVER["PHP_SELF"]."?show=group&group=".$rec["mgroup"]."\">".$rec["mgroup"]."</a>";
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
    $tnmObj1 = new tournamentClass();
    $tnmObj1 -> setTeamID($rec["team_home"]);
    $tnmObj1 -> setMatchID($rec["id"]);
    $teamHomeScore = $tnmObj1 -> getScore();
    $teamAwayScore = $tnmObj1 -> getCompetitorScore();


    if ($rec["match_status"]==4) {
        $matchDetail = "<a href=\"".$_SERVER["PHP_SELF"]."?show=match&match=".$rec["id"]."\"><b>".$teamHomeScore." - ".$teamAwayScore."</b></a>";
    } else {
        $matchDetail = "<a href=\"".$_SERVER["PHP_SELF"]."?show=match&match=".$rec["id"]."\">V</a>";
    }
    
?>
                    <tr>
                        <td><?echo $no;?></td>
                        <td><?echo $matchTime;?></td>
                        <td><?echo $matchGroup;?></td>
                        <td><?echo $sideA;?></td>
                        <td><?echo $matchDetail;?></td>
                        <td><?echo $sideB;?></td>
                        <td><?echo $venue;?></td>
                    </tr>
<?
    $no++;
}
mysql_free_result($result);
?>
                    
                </tbody>

            </table>

        </div> <!-- End #tab1 -->

<?
    if ($getTab==2) {
        $tab2Cls = "tab-content default-tab";
    } else {
        $tab2Cls = "tab-content";
    }
?>

        <div class="<?echo $tab2Cls;?>" id="tab2">

<?
    $sql = "Select * From wc2010_stage Where id != 1 Order By id ASC";
    $result = mysql_query($sql) or die("Query failed");
    while ($rec = mysql_fetch_array($result, MYSQL_ASSOC)) {
?>
            <div class="content-box">
                <div class="content-box-header">
                    <h3><?echo $rec["name"];?></h3>
                    <div class="clear"></div>
                </div> <!-- End .content-box-header -->
                <div class="content-box-content">
<?
        $sql2 = "SELECT A.id, team_home, team_away, stadium, remark_home, remark_away, match_status";
        $sql2 .= " , B.name AS hteam, B.short_name AS hteams, C.name AS ateam, C.short_name AS ateams";
        $sql2 .= " , D.name AS venue_name, D.city AS venue_city";
        $sql2 .= " , DATE_FORMAT(match_datetime_gmt, '%e') AS mday, DATE_FORMAT(match_datetime_gmt, '%c') AS mmonth, DATE_FORMAT(match_datetime_gmt, '%Y') AS myear";
        $sql2 .= " , DATE_FORMAT(match_datetime_gmt, '%H') AS mhour, DATE_FORMAT(match_datetime_gmt, '%i') AS mmin, DATE_FORMAT(match_datetime_gmt, '%s') AS msec";
        $sql2 .= " FROM wc2010_match A";
        $sql2 .= " LEFT JOIN wc2010_national_team B";
        $sql2 .= " ON A.team_home = B.id";
        $sql2 .= " LEFT JOIN wc2010_national_team C";
        $sql2 .= " ON A.team_away = C.id";
        $sql2 .= " INNER JOIN wc2010_stadium D";
        $sql2 .= " ON A.stadium = D.id";
        $sql2 .= " WHERE stage = ".$rec["id"];
        $sql2 .= " ORDER BY match_datetime_gmt ASC";
        $result2 = mysql_query($sql2) or die("Query failed");
?>
            <table>
                <thead>
                    <tr>
                        <th>Match</th>
                        <th>Date-Time [<?echo $tZone;?>]</th>
                        <th></th>
                        <th>Result</th>
                        <th></th>
                        <th>Venue</th>
                    </tr>

                </thead>

                <tfoot>
                    <tr>
                        <td colspan="6">
                            <div class="bulk-actions align-left">
                                Timezone :
                                <a href="<?echo $_SERVER["PHP_SELF"]."?show=match&tab=".$getTab."&tz=1";?>">THAILAND</a>
                                |
                                <a href="<?echo $_SERVER["PHP_SELF"]."?show=match&tab=".$getTab."&tz=2";?>">SOUTH AFRICA</a>
                                |
                                <a href="<?echo $_SERVER["PHP_SELF"]."?show=match&tab=".$getTab."&tz=3";?>">GMT</a>
                            </div>
                            <div class="clear"></div>
                        </td>
                    </tr>
                </tfoot>

                <tbody>
<?
        while ($rec2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
            if ($rec2["team_home"]!="") {
                $sideA = "<a href=\"".$_SERVER["PHP_SELF"]."?show=team&team=".$rec2["team_home"]."\"><img src=\"images/team/flag/19/".strtolower($rec2["hteams"]).".gif\">&nbsp;&nbsp;".$rec2["hteam"]."</a>";
            } else {
                $sideA = $rec2["remark_home"];
            }
            if ($rec2["team_away"]!="") {
                $sideB = "<a href=\"".$_SERVER["PHP_SELF"]."?show=team&team=".$rec2["team_away"]."\"><img src=\"images/team/flag/19/".strtolower($rec2["ateams"]).".gif\">&nbsp;&nbsp;".$rec2["ateam"]."</a>";
            } else {
                $sideB = $rec2["remark_away"];
            }

            $venue = "<a href=\"".$_SERVER["PHP_SELF"]."?show=venue&venue=".$rec2["stadium"]."\">".$rec2["venue_name"]."</a>";

            $mGmtStmp = mktime ($rec2["mhour"], $rec2["mmin"], $rec2["msec"], $rec2["mmonth"], $rec2["mday"], $rec2["myear"]);
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

            if ($rec2["match_status"]==4) {

                $tnmObj2 = new tournamentClass();
                $tnmObj2 -> setTeamID($rec2["team_home"]);
                $tnmObj2 -> setMatchID($rec2["id"]);
                $teamHomeScore = $tnmObj2 -> getScore();
                $teamAwayScore = $tnmObj2 -> getCompetitorScore();

                $matchDetail = "<a href=\"".$_SERVER["PHP_SELF"]."?show=match&match=".$rec2["id"]."\"><b>".$teamHomeScore." - ".$teamAwayScore."</b></a>";
            } else {
                $matchDetail = "<a href=\"".$_SERVER["PHP_SELF"]."?show=match&match=".$rec2["id"]."\">V</a>";
            }
?>
                    <tr>
                        <td><?echo $no;?></td>
                        <td><?echo $matchTime;?></td>
                        <td><?echo $sideA;?></td>
                        <td><?echo $matchDetail;?></td>
                        <td><?echo $sideB;?></td>
                        <td><?echo $venue;?></td>
                    </tr>
<?
            $no++;
        }
        mysql_free_result($result2);
?>

                </tbody>
            </table>

                </div>
            </div>
<?
    }
    mysql_free_result($result);
?>

        </div> <!-- End #tab2 -->

    </div> <!-- End .content-box-content -->

</div> <!-- End .content-box -->
<?
    mysql_close();
?>