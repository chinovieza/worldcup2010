<?php
require_once("./config/db.inc.php");
$colPerRow = 2;
?>
<div class="content-box"><!-- Start Content Box -->

    <div class="content-box-header">

        <h3>Stadiums</h3>

        <div class="clear"></div>

    </div> <!-- End .content-box-header -->
    <div class="content-box-content">
        <?
        $sql = "SELECT id, name, city";
        $sql .= " FROM wc2010_stadium";
        $sql .= " Order By name ASC";

        $result = mysql_query($sql) or die("Query failed");
        ?>
        <table>
            <tbody>
                <?
                $colCheck = $colPerRow + 1;
                $colRun = 0;
                while ($rec = mysql_fetch_array($result, MYSQL_ASSOC)) {
                    $aTeam = "<a href=\"".$_SERVER["PHP_SELF"]."?show=venue&venue=".$rec["id"]."\"><img src=\"./images/stadium/".$rec["id"]."b.jpg\"><br>".$rec["name"]."<br>".$rec["city"]."</a>";

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
<?
    mysql_close();
?>