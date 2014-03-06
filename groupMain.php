<?php
require_once("./config/db.inc.php");
require_once("./library/tournamentClass.php");


?>
<div class="content-box"><!-- Start Content Box -->

    <div class="content-box-header">

        <h3>GROUPS</h3>
        <div class="clear"></div>
    </div> <!-- End .content-box-header -->
<?
    $sql = "Select group_stage From wc2010_national_team Group By group_stage Order By group_stage ASC";
    $result = mysql_query($sql) or die("Query failed 1");
    while ($rec = mysql_fetch_array($result, MYSQL_ASSOC)) {
?>
    <div class="content-box-content">


        <div class="content-box">
            <div class="content-box-header">
                <h3><?echo "GROUP ".$rec["group_stage"];?></h3>
                <div class="clear"></div>
            </div> <!-- End .content-box-header -->
            <div class="content-box-content">

<?
        $sql2 = "Select id, name, short_name, debug_coefficient1, debug_coefficient2";
        $sql2 .= " From wc2010_national_team";
        $sql2 .= " Where group_stage = '".$rec["group_stage"]."'";
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
                        <th></th>
                        <th>P</th>
                        <th>W</th>
                        <th>D</th>
                        <th>L</th>
                        <th>GS</th>
                        <th>GA</th>
                        <th>GD</th>
                        <th>Pts</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td colspan="9">
                            <div class="bulk-actions align-left">P: Games Played, W: Wins, D: Draws, L: Losses, GS: Goals Scored, GA: Goals Against, GD: Goal Difference, Pts: Points</div>
                            <div class="clear"></div>
                        </td>
                        <td>
                            <div class="bulk-actions align-right"><a href="<?echo $_SERVER["PHP_SELF"]."?show=group&group=".$rec["group_stage"];?>"><?echo "Group ".$rec["group_stage"]." detail &gt;&gt;";?></a></div>
                            <div class="clear"></div>
                        </td>
                    </tr>
                </tfoot>
                <tbody>
<?
        arsort($gTeamScore);
        $no = 1;
        foreach($gTeamScore as $teamID => $teamScore) {
            $teamShow = "<a href=\"".$_SERVER["PHP_SELF"]."?show=team&team=".$teamID."\"><img src=\"images/team/flag/19/".strtolower($gTeamProfile[$teamID]["sname"]).".gif\">&nbsp;&nbsp;".$gTeamProfile[$teamID]["fname"]."</a>";

?>
                    <tr>
                        <td><?echo $no.".";?></td>
                        <td><?echo $teamShow;?></td>
                        <td><?echo $gTeamProfile[$teamID]["p"];?></td>
                        <td><?echo $gTeamProfile[$teamID]["w"];?></td>
                        <td><?echo $gTeamProfile[$teamID]["d"];?></td>
                        <td><?echo $gTeamProfile[$teamID]["l"];?></td>
                        <td><?echo $gTeamProfile[$teamID]["gs"];?></td>
                        <td><?echo $gTeamProfile[$teamID]["ga"];?></td>
                        <td><?echo $teamScore[1];?></td>
                        <td><b><?echo $teamScore[0];?></b></td>
                    </tr>
<?
            $no++;
        }
?>
                </tbody>
                </table>

            </div>
        </div>



    </div> <!-- End .content-box-content -->
<?
    }
    mysql_free_result($result)
?>
</div> <!-- End .content-box -->
<?
mysql_close();
?>