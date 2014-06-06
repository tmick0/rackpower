<?php

set_title("main");

// print rack position of entity from reference by id
function print_ref_pos($id){
    if($id != 0 && $q = sql_query("SELECT `Rack`,`Position` FROM `entities` WHERE `ID` = '$id'")){
        $r = mysqli_fetch_row($q);
        if($r[1] < 10) $r[1] = "0{$r[1]}";
        echo "{$r[0]}.{$r[1]}";
        mysqli_free_result($q);
    }
    else{
        echo "-";
    }    
}

// count bits set in an integer (used in RefFlags)
function bits_set($n){
    $s = 0;
    while($n > 0){
        $s += $n & 0x01;
        $n = $n >> 1;
    }
    return $s;
}

// calculate total load of a UPS
function calc_ups_load($id){
    // select all references to the UPS
    $q = sql_query("SELECT * FROM `entities` WHERE `Ref1` = '$id' OR `Ref2` = '$id' OR `Ref3` = '$id' OR `Ref4` = '$id'");
    $sum = 0;
    while($r = mysqli_fetch_array($q)){
        // count the machine's active references to divide up its total load
        $active_refs = bits_set($r['RefFlags']);
        if($active_refs != 0){
            $load_per_ref = $r['TotalLoad'] / $active_refs;
            // for each possible reference, check if it is a reference to this machine and that it is active
            if($r['Ref1'] == $id && ($r['RefFlags'] & 0x01) ) $sum += $load_per_ref;
            if($r['Ref2'] == $id && ($r['RefFlags'] & 0x02) ) $sum += $load_per_ref;
            if($r['Ref3'] == $id && ($r['RefFlags'] & 0x04) ) $sum += $load_per_ref;
            if($r['Ref4'] == $id && ($r['RefFlags'] & 0x08) ) $sum += $load_per_ref;
        }
    }
    mysqli_free_result($q);
    return $sum;
}

// check if the given refernce n is active for entity id and print it out appropriately
function print_ref_status($id, $n){
    $q = sql_query("SELECT `Ref$n`, `RefFlags`,`TotalLoad` FROM `entities` WHERE `ID`='$id'");
    $r = mysqli_fetch_array($q);
    $active_refs = bits_set($r['RefFlags']);
    $load_per_ref = 0;
    if($active_refs > 0)
        $load_per_ref = $r['TotalLoad'] / $active_refs;
    if(($r['RefFlags'] & pow(2, $n-1)) == 0 && $r["Ref$n"] != 0){
        echo "<s title='Disabled'>";
        print_ref_pos($r["Ref$n"]);
        echo "</s>";
    }
    elseif($r["Ref$n"] != 0){
        echo "<span title='$load_per_ref W'>";
        print_ref_pos($r["Ref$n"]);
        echo "</span>";
    }
    else{
        echo "-";
    }
    mysqli_free_result($q);
}

// print a rack row(one entity)
function generate_rack_row($rack_idx, $slot_idx, $hide_r1, $hide_r2, $hide_r3, $hide_r4){
    $rack_idx = sql_esc($rack_idx);
    $slot_idx = sql_esc($slot_idx);
    
    $n_col = 9 - $hide_r1 - $hide_r2 - $hide_r3 - $hide_r4;
    
    $r = sql_query("SELECT * FROM `entities` WHERE `Rack`='$rack_idx' AND `Position`<='$slot_idx' AND (`Position` + `Height`) > '$slot_idx'");

    if($slot_idx < 10) $slot_idx = "0{$slot_idx}";
    
    if($r && mysqli_num_rows($r) > 0){
        $item = mysqli_fetch_array($r);
        if($item['Position'] + $item['Height'] - 1 == $slot_idx){
            $bgcolor = "";
            if($item['Group'] != 0){
                $cq = sql_query("SELECT `Color` FROM `groups` WHERE `ID`='{$item['Group']}'");
                $r = mysqli_fetch_row($cq);
                $bgcolor = "background-color:{$r[0]};";
                mysqli_free_result($cq);
            }

            if($item['Height'] < 2) $bgcolor.="white-space:nowrap;";
            $bgcolor = "style='$bgcolor'";

            $title = "";
            if(strlen($item['Comment'])){
                $title = "title=\"".htmlentities($item['Comment'])."\"";
            }
            
            echo "<tr class='rack_row'>";
            echo "<td>$slot_idx</td>";
            echo "<td $bgcolor rowspan='{$item['Height']}' style='white-space:nowrap;' $title><a href='?p=edit&id={$item['ID']}' onclick='return windowpop(this.href)'>{$item['Hardware']}</a></td>";
            
            if($item['Type'] == 1){
                // type==1 -> server

                if(!$hide_r1){
                    echo "<td $bgcolor rowspan='{$item['Height']}'>";
                    echo print_ref_status($item['ID'], 1);
                    echo "</td>";
                }

                if(!$hide_r2){
                    echo "<td $bgcolor rowspan='{$item['Height']}'>";
                    echo print_ref_status($item['ID'], 2);
                    echo "</td>";
                }

                if(!$hide_r3){
                    echo "<td $bgcolor rowspan='{$item['Height']}'>";
                    echo print_ref_status($item['ID'], 3);
                    echo "</td>";
                }

                if(!$hide_r4){
                    echo "<td $bgcolor rowspan='{$item['Height']}'>";
                    echo print_ref_status($item['ID'], 4);
                    echo "</td>";
                }
                
                echo "<td $bgcolor rowspan='{$item['Height']}'>{$item['TotalLoad']}</td>";
                
                echo "<td $bgcolor rowspan='{$item['Height']}'>";

                $q = sql_query("SELECT `ID` FROM `entities` WHERE `ID` = '{$item['Ref1']}' OR `ID`='{$item['Ref2']}' OR `ID`='{$item['Ref3']}' OR `ID`='{$item['Ref4']}'");
                $min_id = 0;
                $min_runtime = 0;
                while($r = mysqli_fetch_array($q)){
                    // skip if not active
                    if($item['Ref1'] == $r['ID'] && ($item['RefFlags'] & 0x01) == 0) continue;
                    if($item['Ref2'] == $r['ID'] && ($item['RefFlags'] & 0x02) == 0) continue;
                    if($item['Ref3'] == $r['ID'] && ($item['RefFlags'] & 0x04) == 0) continue;
                    if($item['Ref4'] == $r['ID'] && ($item['RefFlags'] & 0x08) == 0) continue;
                    
                    $sum = calc_ups_load($r['ID']);
                    $this_rt = apply_formula($r['ID'], $sum);
                    if($min_id == 0 || $this_rt < $min_runtime){
                        $min_runtime = round($this_rt);
                        $min_id = $r['ID'];
                    }   
                }
                mysqli_free_result($q);
                echo "$min_runtime</td>";
            }
            
            elseif($item['Type'] == 2){
                // type==2 -> ups
                $sum= calc_ups_load($item['ID']);
                $span_a = $n_col - 5;
                
                echo "<td $bgcolor rowspan='{$item['Height']}' colspan='$span_a'>";
                echo $item['Capacity'];
                echo "</td>";

                if(isset($_GET['prefer_percent'])){
                    echo "<td $bgcolor rowspan='{$item['Height']}' title='$sum'>";
                    echo round($sum/$item['Capacity']*100,1);
                    echo "%</td>";
                }
                else{
                    echo "<td $bgcolor rowspan='{$item['Height']}' title='".round($sum/$item['Capacity']*100,1)."%'>";
                    echo "$sum</td>";
                }
                
                echo "<td $bgcolor rowspan='{$item['Height']}'>";
                echo round(apply_formula($item['ID'], $sum));
                echo "</td>";
            }
            
            else{
                // other
                echo "<td $bgcolor rowspan='{$item['Height']}' colspan='7'>&nbsp;</td>";
            }
            echo "</tr>";
        }
        else{
            echo "<tr class='rack_row'><td>$slot_idx</td></tr>";
        }
    }
    else{
        echo "<tr class='rack_empty_row'><td>$slot_idx</td><td colspan='8'>&nbsp;</td></tr>";
    }
}

// print one rack
function generate_rack_table($idx){
    // determine which references we need to print in this rack
    $h = array(0,0,0,0);
    $n_col = 9;

    if(!isset($_GET['show_hidden_cols'])){
        for($i = 0; $i < 4; $i++){
            $i_ = $i+1;
            $q = sql_query("SELECT COUNT(*) FROM `entities` WHERE `Rack` = '$idx' AND `Ref$i_` != 0");
          
            $count = mysqli_fetch_row($q);
            $count = $count[0];
         
            mysqli_free_result($q);
            if($count < 1){
                $h[$i] = 1;
                $n_col --;
            }
           
        }
    }

    echo "<div class='rack'>";
    echo "<table>";
    echo "<tr class='rack_title'>";
    echo "<td colspan='$n_col'>Rack $idx</td>";
    echo "</tr>";
    echo "<tr class='rack_head'>";
    echo "<td>&nbsp;</td><td>Hardware</td>";

    for($i = 0; $i < 4; $i++){
        if(!$h[$i]){
            echo "<td>R" . ($i+1) . "</td>";
        }
    }

    echo "<td>Load</td><td>Uptime</td>";
    echo "</tr>";
    for($i = 42; $i >= 0; $i--){
        generate_rack_row($idx, $i, $h[0], $h[1], $h[2], $h[3]);
    }
    echo "</table>";
    echo "</div>";
}

// main script begins here
if(is_user_authed()){
	echo "<div id='header'><h2 class='head'>rackpower</h2><ul class='head'>";
	show_content("head.inc.php");
    echo "</ul></div><div id='main'>";
    echo "<div class='racks_container'>";
    if($racks = sql_query("SELECT `RackId` FROM `racks` ORDER BY `RackId` DESC")){
        while($row = mysqli_fetch_array($racks)){
            generate_rack_table($row['RackId']);
        }
        mysqli_free_result($racks);
    }
    echo "</div></div>";
}
else{
    echo "<div id='header'><h2 class='head'>rackpower</h2><ul class='head'>";
    echo "<li><a href='./?p=login' onclick='return windowpop(this.href)'>Log In</a></li>";
    echo "</ul></div><div id='main'></div>";
}
?>
