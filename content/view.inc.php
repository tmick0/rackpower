<?php

if(!is_user_authed()) exit("not logged in");

// get the value of a text input field
function get_val($id, $field){
    if($id){
        $q = sql_query("SELECT `$field` FROM `entities` WHERE `ID` = '$id'");
        $r = mysqli_fetch_row($q);
        mysqli_free_result($q);
        return $r[0];
    }
    else{
        return "";
    }
}

// check whether the given bit is set in the RefFlags field of an entity
function get_flag_checked($id, $flag){
    return ((get_val($id, "RefFlags") & $flag) != 0);
}

// print reference to the ups which entity $id has attached on reference $n with strikethrough if disabled
function print_ref($id, $n){
    $q1 = sql_query("SELECT `ID`,`Hardware`,`Rack`,`Position` FROM `entities` WHERE `ID` = (SELECT `Ref$n` FROM `entities` WHERE `ID` = '$id')");
    $q2 = sql_query("SELECT `TotalLoad`,`RefFlags` FROM `entities` WHERE `ID`='$id'");
    $r2 = mysqli_fetch_array($q2);
    
    $s = "Undefined";

    $active_refs = bits_set($r2['RefFlags']);
    $load_per_ref = 0;
    if($active_refs > 0)
        $load_per_ref = $r2['TotalLoad'] / $active_refs;

    if(mysqli_num_rows($q1)){
        $r1 = mysqli_fetch_array($q1);

        if($r1['Position'] < 10)
            $r1['Position'] = "0{$r1['Position']}";

        $s = "{$r1['Rack']}.{$r1['Position']} - {$r1['Hardware']}";
    }
    
    mysqli_free_result($q1);
    
    if($s != "Undefined" && !get_flag_checked($id, pow(2,$n-1)))
        $s = "<s>$s</s>";
    elseif($s != "Undefined")
        $s = "$s ($load_per_ref W)";
        
    echo $s;
}

// id of field we're viewing
$mode = 0;
if(isset($_GET['id'])){
    $mode = intval($_GET['id']);;
    set_title("view item");
}
else{
    exit('invalid id');
}


?>

    <h2>View Entity</h2>
    <table class='edit_form'>
    
    <tr>
        <td>Hardware</td>
        <td><?php echo get_val($mode, 'Hardware');?></td>
    </tr>
    
    <tr>
        <td>Type</td>
        <td>
            <?php
                $type = get_val($mode, 'Type');
                switch($type){
                    case 1: echo 'Consumer (Server)'; break;
                    case 2: echo 'Provider (UPS)'; break;
                    case 3: echo 'Other'; break;
                }
            ?>
        </td>
    </tr>

    <tr>
        <td>Group</td>
        <td>
            <?php
                $group = get_val($mode, 'Group');
                $q = sql_query("SELECT `Name` FROM `groups` WHERE `ID` = '$group'");
                $r = mysqli_fetch_row($q);
                echo $r[0];
                mysqli_free_result($q);
            ?>
            </select>
        </td>
    <tr>
        <td>Position</td>
        <td>
            <?php
                echo get_val($mode, 'Rack');
                echo ".";
                echo get_val($mode, 'Position');
            ?>
        </tr>
    </tr>
    
    <tr>
        <td>Height</td>
        <td>
            <?php
                echo get_val($mode, 'Height');
                echo " U";
            ?>
        </td>
        </tr>
    </tr>

    <?php if(get_conf('use_glpi') && $type == 1){ ?>
    <tr>
        <td>GLPI Data</td>
        <td>
            <?php print_glpi_table($mode); ?>
        </td>
    </tr>
    <?php } ?>

    <?php if($type == 1) { ?>
    <tr>
        <td>Consumer Parameters</td>
        <td>
            <table class='provtable glpitable'>
                <tr>
                    <td style='width:144px'>Total Consumption</td>
                    <td><?php echo get_val($mode, 'TotalLoad');?> W</td>
                </tr>
                <tr>
                    <td style='width:144px'>Power Supply 1</td>
                    <td>
                        <?php print_ref($mode,1); ?>
                    </td>
                </tr>

                <tr>
                    <td style='width:144px'>Power Supply 2</td>
                    <td>
                        <?php print_ref($mode,2); ?>
                    </td>
                </tr>

                <tr>
                    <td style='width:144px'>Power Supply 3</td>
                    <td>
                        <?php print_ref($mode,3); ?>
                    </td>
                </tr>

                <tr>
                    <td style='width:144px'>Power Supply 4</td>
                    <td>
                        <?php print_ref($mode,4); ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <?php } elseif($type == 2) { ?>

    <tr>
        <td>Provider Parameters</td>
        <td>
            <table class='provtable glpitable'>
                <tr>
                    <td>Capacity</td>
                    <td>
                         <?php echo get_val($mode, 'Capacity'); ?>
                    </td>
                </tr>
                <tr>
                    <td>Runtime</td>
                    <td>
                        <?php echo get_val($mode, 'FormulaA'); ?> * e^(<?php echo get_val($mode, 'FormulaB');?> * W)
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <?php } ?>

    <tr>
        <td>Comments</td>
        <td>
            <pre><?php echo get_val($mode, 'Comment'); ?></pre>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
        <td><a href='./?p=edit&id=<?php echo $mode; ?>'>Edit &rarr;</a></td>
    </tr>
    
    </table>
</form>
