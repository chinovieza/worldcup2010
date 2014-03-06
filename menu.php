<ul id="main-nav">  <!-- Accordion Menu -->
    <?
        if ($getShow=="match") {
            $menuCls = "nav-top-item current";
        } else {
            $menuCls = "nav-top-item ";
        }
    ?>
    <li>
        <a href="<?echo $_SERVER["PHP_SELF"]."?show=match";?>" class="<?echo $menuCls;?>"> <!-- Add the class "no-submenu" to menu items with no sub menu -->
						Matches
        </a>
        <?
            $liLink1Cls = "";
            $liLink2Cls = "";
            if ($getTab==1) {
                $liLink1Cls = "class=\"current\"";
            } else if ($getTab==2) {
                $liLink2Cls = "class=\"current\"";
            }
        ?>
        <ul>
            <li><a href="<?echo $_SERVER["PHP_SELF"]."?show=match&tab=1";?>" <?echo $liLink1Cls;?>>Group Stage</a></li>
            <li><a href="<?echo $_SERVER["PHP_SELF"]."?show=match&tab=2";?>" <?echo $liLink2Cls;?>>Knockout Stage</a></li> <!-- Add class "current" to sub menu items also -->
        </ul>
    </li>
    <?
        if ($getShow=="group") {
            $menuCls = "nav-top-item no-submenu current";
        } else {
            $menuCls = "nav-top-item no-submenu";
        }
    ?>
    <li>
        <a href="<?echo $_SERVER["PHP_SELF"]."?show=group";?>" class="<?echo $menuCls;?>"> <!-- Add the class "no-submenu" to menu items with no sub menu -->
						Groups
        </a>
    </li>
    <?
        if ($getShow=="team") {
            $menuCls = "nav-top-item no-submenu current";
        } else {
            $menuCls = "nav-top-item no-submenu";
        }
    ?>
    <li>
        <a href="<?echo $_SERVER["PHP_SELF"]."?show=team";?>" class="<?echo $menuCls;?>"> <!-- Add the class "no-submenu" to menu items with no sub menu -->
						Teams
        </a>
    </li>
    <?
        if ($getShow=="venue") {
            $menuCls = "nav-top-item no-submenu current";
        } else {
            $menuCls = "nav-top-item no-submenu";
        }
    ?>
    <li>
        <a href="<?echo $_SERVER["PHP_SELF"]."?show=venue";?>" class="<?echo $menuCls;?>"> <!-- Add the class "no-submenu" to menu items with no sub menu -->
						Stadiums
        </a>
    </li>

</ul> <!-- End #main-nav -->
