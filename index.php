<?
require_once("./config/db.inc.php");
require_once("./library/utilityFunction.php");

$getShow = isset($_GET["show"]) ? $_GET["show"] : "match";
$getTeam = isset($_GET["team"]) ? $_GET["team"] : "";
$getVenue = isset($_GET["venue"]) ? $_GET["venue"] : "";
$getGroup = isset($_GET["group"]) ? $_GET["group"] : "";
$getMatch = isset($_GET["match"]) ? $_GET["match"] : "";
$getTab = isset($_GET["tab"]) ? $_GET["tab"] : "";

if ($getShow=="match") {
    if ($getMatch!="") {
        $webPageTitle = "ChinoVieza.com - The matches of 2010 FIFA World Cup South Africa";
    } else {
        $webPageTitle = "ChinoVieza.com - The matches of 2010 FIFA World Cup South Africa";
    }
} else if ($getShow=="group") {
    if ($getGroup!="") {
        $webPageTitle = "ChinoVieza.com - Groups ".$getGroup." : 2010 FIFA World Cup South Africa&#153; teams";
    } else {
        $webPageTitle = "ChinoVieza.com - The groups team of 2010 FIFA World Cup South Africa";
    }
} else if ($getShow=="team") {
    if ($getTeam!="") {
        $webPageTitle = "ChinoVieza.com - ".getTeamName($getTeam)." : 2010 FIFA World Cup South Africa&#153; teams";
    } else {
        $webPageTitle = "ChinoVieza.com - 2010 FIFA World Cup South Africa&#153; teams";
    }
} else if ($getShow=="venue") {
    if ($getVenue!="") {
        $webPageTitle = "ChinoVieza.com - ".getStadiumName($getVenue)." : the stadiums for the 2010 FIFA World Cup South Africa";
    } else {
        $webPageTitle = "ChinoVieza.com - The stadiums for the 2010 FIFA World Cup South Africa";
    }
} else if ($getShow=="player") {
    $webPageTitle = "ChinoVieza.com - 2010 FIFA World Cup South Africa&#153; - Players";
} else {
    $webPageTitle = "ChinoVieza.com";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

        <title><?echo $webPageTitle;?></title>

        <!--                       CSS                       -->

        <!-- Reset Stylesheet -->
        <link rel="stylesheet" href="resources/css/reset.css" type="text/css" media="screen" />

        <!-- Main Stylesheet -->
        <link rel="stylesheet" href="resources/css/style.css" type="text/css" media="screen" />

        <!-- Invalid Stylesheet. This makes stuff look pretty. Remove it if you want the CSS completely valid -->
        <link rel="stylesheet" href="resources/css/invalid.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="resources/css/red.css" type="text/css" media="screen" />

        <!-- Colour Schemes

		Default colour scheme is green. Uncomment prefered stylesheet to use it.

		<link rel="stylesheet" href="resources/css/blue.css" type="text/css" media="screen" />

		<link rel="stylesheet" href="resources/css/red.css" type="text/css" media="screen" />

		-->

        <!-- Internet Explorer Fixes Stylesheet -->

        <!--[if lte IE 7]>
			<link rel="stylesheet" href="resources/css/ie.css" type="text/css" media="screen" />
		<![endif]-->

        <!--                       Javascripts                       -->

        <!-- jQuery -->
        <script type="text/javascript" src="resources/scripts/jquery-1.3.2.min.js"></script>

        <!-- jQuery Configuration -->
        <script type="text/javascript" src="resources/scripts/simpla.jquery.configuration.js"></script>

        <!-- Facebox jQuery Plugin -->
        <script type="text/javascript" src="resources/scripts/facebox.js"></script>

        <!-- jQuery WYSIWYG Plugin -->
        <script type="text/javascript" src="resources/scripts/jquery.wysiwyg.js"></script>

        <!-- Internet Explorer .png-fix -->

        <!--[if IE 6]>
                <script type="text/javascript" src="resources/scripts/DD_belatedPNG_0.0.7a.js"></script>
                <script type="text/javascript">
				DD_belatedPNG.fix('.png_bg, img, li');
			</script>
		<![endif]-->

    </head>

    <body><div id="body-wrapper"> <!-- Wrapper for the radial gradient background -->

            <div id="sidebar"><div id="sidebar-wrapper"> <!-- Sidebar with logo and menu -->
                    <!-- Logo (221px wide) -->
                    <img id="logo" src="images/logo/200px-2010_FIFA_World_Cup_logo_svg.png" alt="2010 FIFA WORLD CUP SOUTH AFRICA" />
                    <?include("menu.php");?>
                </div></div> <!-- End #sidebar -->

            <div id="main-content"> <!-- Main Content Section with everything -->

                <noscript> <!-- Show a notification if the user has disabled javascript -->
                    <div class="notification error png_bg">
                        <div>
						Javascript is disabled or is not supported by your browser. Please <a href="http://browsehappy.com/" title="Upgrade to a better browser">upgrade</a> your browser or <a href="http://www.google.com/support/bin/answer.py?answer=23852" title="Enable Javascript in your browser">enable</a> Javascript to navigate the interface properly.
                        </div>
                    </div>
                </noscript>

                <!-- Page Head -->
                <h2>2010 FIFA WORLD CUP SOUTH AFRICA &#153;</h2>
                <p id="page-intro">Brought to you by ChinoVieza.com</p>



                <div class="clear"></div> <!-- End .clear -->
                <?
                if ($getShow=="match") {
                    if ($getMatch!="") {
                        include("matchDetail.php");
                    } else {
                        include("match.php");
                    }
                } else if ($getShow=="group") {
                    if ($getGroup!="") {
                        include("group.php");
                    } else {
                        include("groupMain.php");
                    }
                } else if ($getShow=="team") {
                    if ($getTeam!="") {
                        include("team.php");
                    } else {
                        include("teamMain.php");
                    }
                } else if ($getShow=="venue") {
                    if ($getVenue!="") {
                        include("venue.php");
                    } else {
                        include("venueMain.php");
                    }
                } else if ($getShow=="player") {
                    include("player.php");
                } else {
                    include("match.php");
                }
                ?>
                <div id="footer">
                    <small>
                        &#169; Copyright  &#174; 2010 ChinoVieza.com All rights reserved.
                    </small>
                </div><!-- End #footer -->

            </div> <!-- End #main-content -->

        </div></body>

</html>
