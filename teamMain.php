<?php
require_once("./config/db.inc.php");
require_once("./library/utilityFunction.php");
require_once("./library/tournamentClass.php");
$colPerRow = 4;
$topNum = 10;
?>
<div class="content-box"><!-- Start Content Box -->

    <div class="content-box-header">

        <h3>Teams</h3>

        <div class="clear"></div>

    </div> <!-- End .content-box-header -->
    <div class="content-box-content">
        <?
        $sql = "SELECT id, name, short_name";
        $sql .= " FROM wc2010_national_team";
        $sql .= " Order By name ASC";

        $result = mysql_query($sql) or die("Query failed");
        ?>
        <table>
            <tbody>
                <?
                $colCheck = $colPerRow + 1;
                $colRun = 0;
                while ($rec = mysql_fetch_array($result, MYSQL_ASSOC)) {
                    $aTeam = "<a href=\"".$_SERVER["PHP_SELF"]."?show=team&team=".$rec["id"]."\"><img src=\"images/team/flag/47/".strtolower($rec["short_name"]).".gif\">&nbsp;&nbsp;".$rec["name"]."</a>";

                    if ($colRun==0) {
                        echo "<tr>";
                        $colRun = 1;
                    }
                ?>
                    <td><?echo $aTeam;?></td>
                <?
                    $colRun++;
                    if ($colRun==$colCheck){
                        echo "</tr>";
                        $colRun = 0;
                    }
                }
                while(($colRun!=0) && ($colRun<$colCheck)){
                    echo "<td></td>";
                    $colRun++;
                    if ($colRun==$colCheck){
                        echo "</tr>";
                        $colRun = 0;
                    }
                }

                mysql_free_result($result);
                ?>
            </tbody>
        </table>
    </div>
</div>

<div class="clear"></div>
<div class="content-box column-left">

    <div class="content-box-header"> <!-- Add the class "closed" to the Content box header to have it closed by default -->

        <h3>Top <?echo $topNum;?> Teams Score</h3>

    </div> <!-- End .content-box-header -->
    <div class="clear"></div>
    <div class="content-box-content">
<?
    $sql = "Select id From wc2010_national_team";
    $result = mysql_query($sql) or die("Query failed");
    $teamScoreArr = array();
    while ($rec = mysql_fetch_array($result)) {
        $sql2 = "Select id From wc2010_match";
        $sql2 .= " Where (team_home = ".$rec[0];
        $sql2 .= " Or team_away = ".$rec[0].")";
        $sql2 .= " And match_status = 4";
        $result2 = mysql_query($sql2) or die("Query failed");
        $teamTotalScore = 0;
        while ($rec2 = mysql_fetch_array($result2)) {
            $tournamentObj = new tournamentClass();
            $tournamentObj -> setTeamID($rec[0]);
            $tournamentObj -> setMatchID($rec2[0]);
            $teamTotalScore += $tournamentObj -> getScore();
        }
        mysql_free_result($result2);
        $teamScoreArr[$rec[0]] = $teamTotalScore;
    }
    mysql_free_result($result);
?>
            <table>
            <tbody>
<?
    arsort($teamScoreArr);
    $no = 1;
    foreach($teamScoreArr as $teamID => $teamScore) {
        $sql = "Select name, short_name From wc2010_national_team Where id = ".$teamID;
        $result = mysql_query($sql) or die("Query failed");
        $rec = mysql_fetch_array($result, MYSQL_ASSOC);
        $teamName = $rec["name"];
        $teamShortName = $rec["short_name"];
        mysql_free_result($result);
        $teamShow = "<a href=\"".$_SERVER["PHP_SELF"]."?show=team&team=".$teamID."\"><img src=\"images/team/flag/19/".strtolower($teamShortName).".gif\">&nbsp;&nbsp;".$teamName."</a>";


?>
                    <tr>
                        <td><?echo $no;?></td>
                        <td><?echo $teamShow;?></td>
                        <td><?echo $teamScore;?></td>
                    </tr>
<?
            if($no==$topNum) {
                break;
            } else {
                $no++;
            }

            
        }
?>
                </tbody>
                </table>


    </div> <!-- End .content-box-content -->

</div> <!-- End .content-box -->
<div class="clear"></div>

<?
    mysql_close();
?>
