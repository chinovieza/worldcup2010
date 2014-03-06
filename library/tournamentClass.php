<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of tournamentClass
 *
 * @author Administrator
 */
class tournamentClass {
    //put your code here
    const winPoint = 3;
    const drawPoint = 1;

    var $teamID;
    var $matchID;
    function setTeamID($value){
        $this->teamID = $value;
    }
    function setMatchID($value){
        $this->matchID = $value;
    }
    function getTeamID(){
        return $this->teamID;
    }
    function getMatchID(){
        return $this->matchID;
    }
    function getCountP($stage=0) {
        $sql = "Select count(*) as num";
        $sql .= " From wc2010_match";
        $sql .= " Where match_status = 4";
        $sql .= " And (team_home = ".$this->getTeamID();
        $sql .= " Or team_away = ".$this->getTeamID().")";
        if ($stage==1){
            $sql .= " And stage = 1";   //Group Stage Only;
        }

        $result = mysql_query($sql) or die("Query failed a1");
        $rec = mysql_fetch_array($result);
        $value = $rec[0];
        mysql_free_result($result);

        return $value;
    }
    function getCountMatchResult($stage=0){
        $sql = "Select id";
        $sql .= " From wc2010_match";
        $sql .= " Where match_status = 4";
        $sql .= " And (team_home = ".$this->getTeamID();
        $sql .= " Or team_away = ".$this->getTeamID().")";
        if ($stage==1){
            $sql .= " And stage = 1";   //Group Stage Only;
        }

        $result = mysql_query($sql) or die("Query failed a2");
        $win = $draw = $lost = $goalScored = $goalAgainst = 0;
        while($rec = mysql_fetch_array($result, MYSQL_ASSOC)){
            $this->setMatchID($rec["id"]);
            $ourScore = $this->getScore();
            $theirScore = $this->getCompetitorScore();

            $goalScored += $ourScore;
            $goalAgainst += $theirScore;

            if ($ourScore>$theirScore){
                $win++;
            } else if ($ourScore==$theirScore){
                $draw++;
            } else if ($ourScore<$theirScore){
                $lost++;
            }
        }
        $value = array(
            "w" => $win,
            "d" => $draw,
            "l" => $lost,
            "gs" => $goalScored,
            "ga" => $goalAgainst
        );
        return $value;
    }
    function getScore() {
        $competitor = $this->getcompetitor();

        //Take the score from normal score.
        $sql = "Select count(*) as num";
        $sql .= " From wc2010_score";
        $sql .= " Where match_id = ".$this->getMatchID();
        $sql .= " And team_id = ".$this->getTeamID();
        $sql .= " And score_type <= 4";
        $result = mysql_query($sql) or die("Query failed a3");
        $rec = mysql_fetch_array($result);
        $value1 = $rec[0];
        mysql_free_result($result);

        //Take the score from competitor own goal.
        $sql = "Select count(*) as num";
        $sql .= " From wc2010_score";
        $sql .= " Where match_id = ".$this->getMatchID();
        $sql .= " And team_id = ".$competitor;
        $sql .= " And score_type = 5";
        $result = mysql_query($sql) or die("Query failed a4");
        $rec = mysql_fetch_array($result);
        $value2 = $rec[0];
        mysql_free_result($result);

        return ($value1 + $value2);
    }
    
    function getCompetitorScore(){
        $competitor = $this->getcompetitor();
        
        //Take the score from normal score.
        $sql = "Select count(*) as num";
        $sql .= " From wc2010_score";
        $sql .= " Where match_id = ".$this->getMatchID();
        $sql .= " And team_id = ".$competitor;
        $sql .= " And score_type <= 4";
        $result = mysql_query($sql) or die("Query failed a5");
        $rec = mysql_fetch_array($result);
        $value1 = $rec[0];
        mysql_free_result($result);
        
         //Take the score from our own goal.
        $sql = "Select count(*) as num";
        $sql .= " From wc2010_score";
        $sql .= " Where match_id = ".$this->getMatchID();
        $sql .= " And team_id = ".$this->getTeamID();
        $sql .= " And score_type = 5";
        $result = mysql_query($sql) or die("Query failed a6");
        $rec = mysql_fetch_array($result);
        $value2 = $rec[0];
        mysql_free_result($result);

        return ($value1 + $value2);
    }

    function getcompetitor(){
        //Query competitor
        $sql = "Select team_home, team_away From wc2010_match Where id = ".$this->getMatchID();
        $result = mysql_query($sql) or die("Query failed a7");
        $rec = mysql_fetch_array($result, MYSQL_ASSOC);
        if ($this->getTeamID() == $rec["team_home"]) {
            $competitor = $rec["team_away"];
        } else {
            $competitor = $rec["team_home"];
        }
        mysql_free_result($result);

        return $competitor;
    }


}
?>
<?
//Group Regulations
//The ranking of each team in each group will be determined as follows:
// a) greatest number of points obtained in all group matches;
// b) goal difference in all group matches;
// c) greatest number of goals scored in all group matches.48FINAL COMPETITION
//If two or more teams are equal on the basis of the above three criteria, their
//rankings will be determined as follows:
// d) greatest number of points obtained in the group matches between the
//teams concerned;
// e) goal difference resulting from the group matches between the teams
//concerned;
// f) greater number of goals scored in all group matches between the teams
//concerned;
// g) drawing of lots by the FIFA Organising Committee.
//http://www.fifa.com/mm/document/tournament/competition/56/42/69/fifawcsouthafrica2010inhalt_e.pdf
//ในรอบแบ่งกลุ่มให้เรียงอันดับตามคะแนนก่อน
//- จากนั้นให้ดูผลต่างประตูได้เสีย
//- แล้วค่อยดูว่าทีมไหนยิงประตูมากกว่า
//ถ้ายังเสมอกันตั้งแต่ 2 ทีมขึ้นไป
//- ให้นับคะแนนที่แข่งกันระหว่างทีมคู่กรณี
//- ถ้าเท่ากันให้ดูประตูได้เสียในการแข่งระหว่างทีมคู่กรณี
//- ถ้ายังเท่ากันให้ดูว่าทีมไหนยิงประตูมากที่สุดในการแข่งระหว่างทีมคู่กรณี
//- ถ้ายังเสมอกันให้จับฉลาก
?>


