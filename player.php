<?php
require_once("./config/db.inc.php");
require_once("./config/date.inc.php");
require_once("./library/utilityFunction.php");

$getPlayer = isset($_GET["player"]) ? $_GET["player"] : 1;

$sql = "Select full_name";
$sql .= " From wc2010_player";
$sql .= " Where id = ".$getPlayer;
$result = mysql_query($sql) or die("Query failed");
$rec = mysql_fetch_array($result, MYSQL_ASSOC);
$playerName = $rec["full_name"];
mysql_free_result($result);
?>
<div class="content-box"><!-- Start Content Box -->

    <div class="content-box-content">
        <?

        echo "<h2>".$playerName."</h2>";

        ?>
    </div>
</div> <!-- End .content-box -->
<?
mysql_close();
?>
