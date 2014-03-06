<?php
require_once("./config/db.inc.php");
require_once("./config/date.inc.php");
require_once("./library/utilityFunction.php");
require_once("./library/tournamentClass.php");

$getTeam = isset($_GET["team"]) ? $_GET["team"] : 1;
$getTz = isset($_GET["tz"]) ? $_GET["tz"] : 1;

$sql = "Select name, group_stage, short_name";
$sql .= " From wc2010_national_team";
$sql .= " Where id = ".$getTeam;
$result = mysql_query($sql) or die("Query failed");
$rec = mysql_fetch_array($result, MYSQL_ASSOC);
$teamName = $rec["name"];
$teamGroup = $rec["group_stage"];
$teamShortName = $rec["short_name"];
mysql_free_result($result);
?>

<div class="content-box"><!-- Start Content Box -->

    <div class="content-box-content">
        <?
        echo "<img src=\"./images/team/flag/200/".strtolower($teamShortName).".png\" width=\"100\" style=\"border:1px solid black;\">";
        echo "<img src=\"./images/blank.gif\" width=\"20\">";
        echo "<img src=\"./images/team/logo/".strtolower($teamShortName).".png\" height=\"70\">";
        echo "<br><br>";
        echo "<h2>".$teamName."</h2>";
        echo "( ".$teamShortName." )<br><br>";
        echo "<a href=".$_SERVER["PHP_SELF"]."?show=group&group=".$teamGroup.">GROUP : ".$teamGroup."</a>";
        ?>
    </div>
</div> <!-- End .content-box -->
<div class="content-box column-left">

    <div class="content-box-header">

        <h3>Fixtures and Results</h3>

    </div> <!-- End .content-box-header -->

    <div class="content-box-content">

        <?
        $sql = "SELECT A.id, team_home, team_away, stadium, match_status";
        $sql .= " , B.short_name AS hteams, C.short_name AS ateams";
        $sql .= " , DATE_FORMAT(match_datetime_gmt, '%e') AS mday, DATE_FORMAT(match_datetime_gmt, '%c') AS mmonth, DATE_FORMAT(match_datetime_gmt, '%Y') AS myear";
        $sql .= " , DATE_FORMAT(match_datetime_gmt, '%H') AS mhour, DATE_FORMAT(match_datetime_gmt, '%i') AS mmin, DATE_FORMAT(match_datetime_gmt, '%s') AS msec";
        $sql .= " FROM wc2010_match A";
        $sql .= " INNER JOIN wc2010_national_team B";
        $sql .= " ON A.team_home = B.id";
        $sql .= " INNER JOIN wc2010_national_team C";
        $sql .= " ON A.team_away = C.id";
        $sql .= " WHERE A.team_home = ".$getTeam;
        $sql .= " OR A.team_away = ".$getTeam;
        $sql .= " ORDER BY match_datetime_gmt ASC";

        $result = mysql_query($sql) or die("Query failed");
        ?>
        <table>
            <tfoot>
                <tr>
                    <td colspan="6">
                        <div class="bulk-actions align-left">
                            Timezone :
                            <a href="<?echo $_SERVER["PHP_SELF"]."?show=team&team=".$getTeam."&tz=1";?>">TH</a>
                            |
                            <a href="<?echo $_SERVER["PHP_SELF"]."?show=team&team=".$getTeam."&tz=2";?>">SA</a>
                            |
                            <a href="<?echo $_SERVER["PHP_SELF"]."?show=team&team=".$getTeam."&tz=3";?>">GMT</a>
                        </div>
                        <div class="clear"></div>
                    </td>
                </tr>
            </tfoot>
            <?
            while ($rec = mysql_fetch_array($result, MYSQL_ASSOC)) {
                $sideA = "<a href=\"".$_SERVER["PHP_SELF"]."?show=team&team=".$rec["team_home"]."\"><img src=\"images/team/flag/19/".strtolower($rec["hteams"]).".gif\">&nbsp;&nbsp;".$rec["hteams"]."</a>";
                $sideB = "<a href=\"".$_SERVER["PHP_SELF"]."?show=team&team=".$rec["team_away"]."\">".$rec["ateams"]."&nbsp;&nbsp;<img src=\"images/team/flag/19/".strtolower($rec["ateams"]).".gif\"></a>";
                $mGmtStmp = mktime ($rec["mhour"], $rec["mmin"], $rec["msec"], $rec["mmonth"], $rec["mday"], $rec["myear"]);
                $mThStmp = $mGmtStmp + $plusStampAsiaBangkok;
                $mSaStmp = $mGmtStmp + $plusStampSouthAfrica;
                if($getTz==2) {
                    $procStmp = $mSaStmp;
                    $tZone = "SA";
                } else if ($getTz==3) {
                    $procStmp = $mGmtStmp;
                    $tZone = "GMT";
                } else {
                    $procStmp = $mThStmp;
                    $tZone = "TH";
                }
                $matchTime = $tZone." ".date("d",$procStmp)."/".date("m",$procStmp)."/".date("Y",$procStmp)." ".date("H",$procStmp).":".date("i",$procStmp);

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
                <td><?echo $matchTime;?></td>
                <td><?echo $sideA;?></td>
                <td><?echo $matchDetail;?></td>
                <td><?echo $sideB;?></td>
            </tr>
                <?
            }
            mysql_free_result($result);
            ?>
        </table>



    </div> <!-- End .content-box-content -->

</div> <!-- End .content-box -->

<div class="content-box column-right">

    <div class="content-box-header"> <!-- Add the class "closed" to the Content box header to have it closed by default -->

        <h3>Standings</h3>

    </div> <!-- End .content-box-header -->

    <div class="content-box-content">

<?
        $sql2 = "Select id, name, short_name, debug_coefficient1, debug_coefficient2";
        $sql2 .= " From wc2010_national_team";
        $sql2 .= " Where group_stage = '".$teamGroup."'";
        $result2 = mysql_query($sql2) or die("Query failed 2");
        $gTeamProfile = array();
        $gTeamScore = array();
        while ($rec2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
            $gTeamProfile[$rec2["id"]]["fname"] = $rec2["name"];
            $gTeamProfile[$rec2["id"]]["sname"] = $rec2["short_name"];

            $tournamentObj = new tournamentClass();
            $tournamentObj -> setTeamID($rec2["id"]);
            $gTeamProfile[$rec2["id"]]["p"] = $tournamentObj -> getCountP(1);
            $matchResult = $tournamentObj -> getCountMatchResult(1);
            $gTeamProfile[$rec2["id"]]["w"] = $matchResult["w"];
            $gTeamProfile[$rec2["id"]]["d"] = $matchResult["d"];
            $gTeamProfile[$rec2["id"]]["l"] = $matchResult["l"];
            $gTeamProfile[$rec2["id"]]["gs"] = $matchResult["gs"];
            $gTeamProfile[$rec2["id"]]["ga"] = $matchResult["ga"];
            $gd = $matchResult["gs"] - $matchResult["ga"];
            $pts = ($matchResult["w"]*tournamentClass::winPoint)+($matchResult["d"]*tournamentClass::drawPoint);
            $gTeamScore[$rec2["id"]] = array($pts,$gd,$matchResult["gs"],$rec2["debug_coefficient1"],$rec2["debug_coefficient2"]);
        }
        mysql_free_result($result2);


        //print_r($gTeamProfile);
        //print_r($gTeamScore);


?>
                <table>
                <thead>
                    <tr>
                        <th></th>
                        <th>P</th>
                        <th>W</th>
                        <th>D</th>
                        <th>L</th>
                        <th>GD</th>
                        <th>Pts</th>
                    </tr>
                </thead>
                <!--<tfoot>
                    <tr>
                        <td colspan="6">
                            <div class="bulk-actions align-left">P: Games Played, W: Wins, D: Draws, L: Losses, GD: Goal Difference, Pts: Points</div>
                            <div class="clear"></div>
                        </td>
                        <td>
                            <div class="bulk-actions align-right"><a href="<?echo $_SERVER["PHP_SELF"]."?show=group&group=".$teamGroup;?>"><?echo "Group ".$teamGroup." detail &gt;&gt;";?></a></div>
                            <div class="clear"></div>
                        </td>
                    </tr>
                </tfoot>-->
                <tfoot>
                    <tr>
                        <td colspan="7">
                            <div class="bulk-actions align-right"><a href="<?echo $_SERVER["PHP_SELF"]."?show=group&group=".$teamGroup;?>"><?echo "More &gt;&gt;";?></a></div>
                            <div class="clear"></div>
                        </td>
                    </tr>
                </tfoot>
                <tbody>
<?
        arsort($gTeamScore);
        $no = 1;
        foreach($gTeamScore as $teamID => $teamScore) {
            $teamShow = "<a href=\"".$_SERVER["PHP_SELF"]."?show=team&team=".$teamID."\"><img src=\"images/team/flag/19/".strtolower($gTeamProfile[$teamID]["sname"]).".gif\">&nbsp;&nbsp;".$gTeamProfile[$teamID]["sname"]."</a>";

?>
                    <tr>
                        <td><?echo $teamShow;?></td>
                        <td><?echo $gTeamProfile[$teamID]["p"];?></td>
                        <td><?echo $gTeamProfile[$teamID]["w"];?></td>
                        <td><?echo $gTeamProfile[$teamID]["d"];?></td>
                        <td><?echo $gTeamProfile[$teamID]["l"];?></td>
                        <td><?echo $teamScore[1];?></td>
                        <td><b><?echo $teamScore[0];?></b></td>
                    </tr>
<?
        }
?>
                </tbody>
                </table>

    </div> <!-- End .content-box-content -->

</div> <!-- End .content-box -->
<div class="clear"></div>

<div class="content-box"><!-- Start Content Box -->

    <div class="content-box-header">

        <h3>Squad List</h3>

        <div class="clear"></div>

    </div> <!-- End .content-box-header -->
    <div class="content-box-content">
        <?
        $sql = "SELECT id, full_name, height, playing_position, club_entirely, national_team_number";
        $sql .= " , DATE_FORMAT(birth_date, '%e') AS bday, DATE_FORMAT(birth_date, '%c') AS bmonth, DATE_FORMAT(birth_date, '%Y') AS byear";
        $sql .= " FROM wc2010_player";
        $sql .= " WHERE national_team = ".$getTeam;
        $sql .= " Order By national_team_number ASC";

        $result = mysql_query($sql) or die("Query failed");
        ?>
        <table>
            <thead>
                <tr>
                    <th>Nr.</th>
                    <th>Name</th>
                    <th>Date of Birth</th>
                    <th>Position</th>
                    <th>Clubs</th>
                    <th>Height</th>
                </tr>
            </thead>
            <tbody>
                <?

                while ($rec = mysql_fetch_array($result, MYSQL_ASSOC)) {
                    $birthDate = $rec["bday"]." ".$monthShortEN[$rec["bmonth"]-1]." ".$rec["byear"]." (".age($rec["bmonth"], $rec["bday"], $rec["byear"]).")";
                    $playerName = "<a href=\"".$_SERVER["PHP_SELF"]."?show=player&player=".$rec["id"]."\">".$rec["full_name"]."</a>";
                    ?>
                <tr>
                    <td><?echo $rec["national_team_number"];?></td>
                    <td><?echo $playerName;?></td>
                    <td><?echo $birthDate;?></td>
                    <td><?echo $rec["playing_position"];?></td>
                    <td><?echo $rec["club_entirely"];?></td>
                    <td><?echo $rec["height"];?></td>
                </tr>
                    <?
                }
                mysql_free_result($result);
                ?>
            </tbody>
        </table>
    </div>
</div>
<?
mysql_close();
?>