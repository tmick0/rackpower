<?php

set_title("racks");

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

function generate_rack_row($rack_idx, $slot_idx){
    $rack_idx = sql_esc($rack_idx);
    $slot_idx = sql_esc($slot_idx);
    
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
            
            echo "<tr class='rack_row'>";
            echo "<td>$slot_idx</td>";
            echo "<td $bgcolor rowspan='{$item['Height']}' style='white-space:nowrap;'><a href='?p=edit&id={$item['ID']}' onclick='return windowpop(this.href)'>{$item['Hardware']}</a></td>";
            
            if($item['Type'] == 1){
                // type==1 -> server
                
                echo "<td $bgcolor rowspan='{$item['Height']}'>";
                echo print_ref_pos($item['Ref1']);
                echo "</td>";
                
                echo "<td $bgcolor rowspan='{$item['Height']}'>";
                echo print_ref_pos($item['Ref2']);
                echo "</td>";

                echo "<td $bgcolor rowspan='{$item['Height']}'>";
                echo print_ref_pos($item['Ref3']);
                echo "</td>";
                
                echo "<td $bgcolor rowspan='{$item['Height']}'>{$item['Used1']}</td>";
                echo "<td $bgcolor rowspan='{$item['Height']}'>{$item['Used2']}</td>";
                echo "<td $bgcolor rowspan='{$item['Height']}'>{$item['Used3']}</td>";
                
                echo "<td $bgcolor rowspan='{$item['Height']}'>";

                $q = sql_query("SELECT `ID` FROM `entities` WHERE `ID` = '{$item['Ref1']}' OR `ID`='{$item['Ref2']}'");
                $min_id = 0;
                $min_runtime = 0;
                while($r = mysqli_fetch_array($q)){
                    $q2a = sql_query("SELECT SUM(`Used1`) FROM `entities` WHERE `Ref1` = '{$r['ID']}'");
                    $q2b = sql_query("SELECT SUM(`Used2`) FROM `entities` WHERE `Ref2` = '{$r['ID']}'");
                    $q2c = sql_query("SELECT SUM(`Used2`) FROM `entities` WHERE `Ref3` = '{$item['ID']}'");
                    $sum = mysqli_fetch_row($q2a)[0] + mysqli_fetch_row($q2b)[0] + mysqli_fetch_row($q2c)[0];
                    mysqli_free_result($q2a); mysqli_free_result($q2b); mysqli_free_result($q2c);
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
                echo "<td $bgcolor rowspan='{$item['Height']}' colspan='3'>";
                echo $item['Capacity'];
                echo "</td>";
                
                echo "<td $bgcolor rowspan='{$item['Height']}' colspan='2'>";
                $q2a = sql_query("SELECT SUM(`Used1`) FROM `entities` WHERE `Ref1` = '{$item['ID']}'");
                $q2b = sql_query("SELECT SUM(`Used2`) FROM `entities` WHERE `Ref2` = '{$item['ID']}'");
                $q2c = sql_query("SELECT SUM(`Used2`) FROM `entities` WHERE `Ref3` = '{$item['ID']}'");
                $sum = mysqli_fetch_row($q2a)[0] + mysqli_fetch_row($q2b)[0] + mysqli_fetch_row($q2c)[0];
                mysqli_free_result($q2a); mysqli_free_result($q2b); mysqli_free_result($q2c);
                echo "$sum</td>";
                
                echo "<td $bgcolor rowspan='{$item['Height']}' colspan='1'>";
                echo round($sum/$item['Capacity']*100,1);
                echo "%</td>";
                
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

function generate_rack_table($idx){
    echo "<div class='rack'>";
    echo "<table>";
    echo "<tr class='rack_title'>";
    echo "<td colspan='9'>Rack $idx</td>";
    echo "</tr>";
    echo "<tr class='rack_head'>";
    echo "<td>&nbsp;</td><td style='min-width:60px;'>Hardware</td><td>Ref1</td><td>Ref2</td><td>Ref3</td><td>Used1</td><td>Used2</td><td>Used3</td><td>Uptime</td>";
    echo "</tr>";
    for($i = 42; $i >= 0; $i--){
        generate_rack_row($idx, $i);
    }
    echo "</table>";
    echo "</div>";
}

if(is_user_authed()){
    echo '<div style="width:100%; overflow:auto;">';
    echo "<div class='racks_container'>";
    if($racks = sql_query("SELECT `RackId` FROM `racks` ORDER BY `RackId` DESC")){
        while($row = mysqli_fetch_array($racks)){
            generate_rack_table($row['RackId']);
        }
        mysqli_free_result($racks);
    }
    echo "</div></div>";

    echo "<p><a href='./?p=edit' onclick='return windowpop(this.href)'>Add new entity &rarr;</a></p>";
echo "<p><a href='./?p=login&logout' onclick='return windowpop(this.href)'>Log out &rarr;</a></p>";
}
else{
    echo "<p><a href='./?p=login' onclick='return windowpop(this.href)'>Log in &rarr;</a></p>";
}
