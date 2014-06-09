<?php
    function build_query($skip,$add){
        // build query string with specified options added/removed
        
        $r = array();

        $opts = array('prefer_percent', 'show_hidden_cols');

        foreach($opts as $v){
            if(isset($_GET[$v]) && $skip != $v){
                $r[] = $v;
            }
        }
        
        if(strlen($add)){
            $r[] = $add;
        }

        $s = "./";
        foreach($r as $v){
            if(strlen($s) == 2)
                $s.= "?$v";
            else
                $s.= "&$v";
        }

        return $s;
    }
?>
<li><a href='./?p=edit' onclick='return windowpop(this.href)'>New Entity</a></li>
<li><a href='./?p=racks' onclick='return windowpop(this.href)'>Manage Racks</a></li>
<li><a href='./?p=groups' onclick='return windowpop(this.href)'>Manage Groups</a></li>
<li>
<?php
    // ?prefer_percent flag toggles between displaying loads as %'s or total W's
    if(isset($_GET['prefer_percent'])){
        $s = build_query('prefer_percent','');
        echo "<a href='$s'>Display Total UPS Load</a>";
    }
    else{
        $s = build_query('','prefer_percent');
        echo "<a href='$s'>Display UPS Load Percent</a>";
    }
?>
</li>
<li>
<?php
    // ?show_hidden_cols flag toggles display of reference columns without any data
    if(isset($_GET['show_hidden_cols'])){
        $s = build_query('show_hidden_cols','');
        echo "<a href='$s'>Hide Empty Columns</a>";
    }
    else{
        $s = build_query('','show_hidden_cols');
        echo "<a href='$s'>Show Empty Columns</a>";
    }
?>
</li>
<li><a href="javascript:addCssClass('#header','display:none')">Hide header</a></li>
<?php
    // show logout link if using internal auth
    if(get_conf('use_auth'))
        echo "<li><a href='./?p=login&logout' onclick='return windowpop(this.href)'>Log Out</a></li>";
?>
