<?php

if(!is_user_authed()) exit("not logged in");

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

function get_selected($id, $field, $val){
    if(get_val($id, $field) == $val)
        return "selected='selected'";
    else
        return "";
}

function get_flag_checked($id, $flag){
    if((get_val($id, "RefFlags") & $flag) != 0)
        return "checked='checked'";
    else
        return "";
}

function print_refs($id,$col){
    $q = sql_query("SELECT `ID`,`Rack`,`Position`,`Hardware` FROM `entities` WHERE `Type` = '2'");
    
    echo "<option value='0' ";
    echo get_selected($id, $col, 0);
    echo ">Undefined</option>";
    
    while($r = mysqli_fetch_array($q)){
        if($r['Position'] < 10) $r['Position'] = "0{$r['Position']}";
        echo "<option value='{$r['ID']}' ";
        echo get_selected($id, $col, $r['ID']);
        echo ">{$r['Rack']}.{$r['Position']} - {$r['Hardware']}</option>";
    }
    mysqli_free_result($q);
}

function calc_flags(){
    $sum = 0;
    if(isset($_POST['ref1_f']) && $_POST['ref1_f'] != 0) $sum += 1;
    if(isset($_POST['ref2_f']) && $_POST['ref2_f'] != 0) $sum += 2;
    if(isset($_POST['ref3_f']) && $_POST['ref3_f'] != 0) $sum += 4;
    if(isset($_POST['ref4_f']) && $_POST['ref4_f'] != 0) $sum += 8;
    return $sum;
}

$mode = 0;
$okay = 1;

if(isset($_GET['id'])){
    $mode = intval($_GET['id']);;
    set_title("edit item");
}
else{
    set_title("add item");
}

if(isset($_GET['post']) && $_POST['action'] == "Save"){
    $save = array();
    
    $save['Type'] = $_POST['type'];
    $save['Rack'] = $_POST['rack'];
    $save['Position'] = $_POST['position'];
    $save['Height'] = $_POST['height'];
    $save['Hardware'] = $_POST['hardware'];
    $save['Group'] = $_POST['group'];
    $save['Comment'] = strip_tags($_POST['comment']);
    
    if($_POST['type'] == 1){
        //consumer
        $save['Ref1'] = intval($_POST['ref1']);
        $save['Ref2'] = intval($_POST['ref2']);
        $save['Ref3'] = intval($_POST['ref3']);
        $save['Ref4'] = intval($_POST['ref4']);
        $save['RefFlags'] = calc_flags();
        $save['TotalLoad'] = intval($_POST['load']);
        $save['Capacity'] = 0;
        $save['FormulaA'] = 0;
        $save['FormulaB'] = 0;
    }
    elseif($_POST['type'] == 2){
        //provider
        $save['Ref1'] = 0;
        $save['Ref2'] = 0;
        $save['Ref3'] = 0;
        $save['Ref4'] = 0;
        $save['RefFlags'] = 0;
        $save['TotalLoad'] = 0;
        $save['Capacity'] = $_POST['capacity'];
        $save['FormulaA'] = $_POST['formulaa'];
        $save['FormulaB'] = $_POST['formulab'];
    }
    else{
        //other
        $save['Ref1'] = 0;
        $save['Ref2'] = 0;
        $save['Ref3'] = 0;
        $save['Ref4'] = 0;
        $save['RefFlags'] = 0;
        $save['TotalLoad'] = 0;
        $save['Capacity'] = 0;
        $save['FormulaA'] = 0;
        $save['FormulaB'] = 0;
    }

    // check if space is occupied
    for($i = 0; $i < $save['Height']; $i++){
        $p = $save['Position'] + $i;
        $q1 = sql_query(
            "SELECT * FROM `entities` WHERE `Rack`='{$save['Rack']}' AND `Position`<='$p' AND ".
            " (`Position` + `Height`) > '$p' AND `ID` != '$mode'");
        if(mysqli_num_rows($q1)){
            echo "Error: The space ({$save['Rack']}.$p) is already occupied by another entity<br/>";
            $okay = 0;
            break;
        }
    }

    if($okay){
        // build query
        $qs = "";
        if($mode){
            //update
            $qs = "UPDATE `entities` SET ";
            foreach($save as $i => $v){
                $v = sql_esc($v);
                $qs .= "`$i` = '$v', ";
            }
            $qs = substr($qs, 0, -2);
            $qs.= " WHERE `ID` = '$mode'";
        }
        else{
            //insert
            $qs = "INSERT INTO `entities` SET ";
            foreach($save as $i => $v){
                $v = sql_esc($v);
                $qs .= "`$i` = '$v', ";
            }
            $qs = substr($qs, 0, -2);
        }
        $q = sql_query($qs);

        if(mysqli_errno(sql())){
            // query failure
            echo mysqli_error(sql());
            echo "<br/><a href='javascript:window.history.go(-1)'>Go back &rarr;</a>";
        }
        else{
            // success
            echo "Changes saved.<br/>";
            echo "<script type='text/javascript'>window.opener.location.reload();</script>";
            echo "<a href='./?' onclick='window.close();return false;'>Continue &rarr;</a><br/>";
            if(!$mode)
                echo "<a href='./?' onclick='window.history.go(-1); return false;'>Add another &rarr;</a><br/>";
        }
    }
    else{
        // input validation error
        echo "<a href='javascript:window.history.go(-1)'>Go back &rarr;</a>";
    }
}
elseif(isset($_GET['post']) && $_POST['action'] == "Delete"){
    sql_query("DELETE FROM `entities` WHERE `ID` = '$mode'");
     if(mysqli_errno(sql())){
            // query failure
            echo mysqli_error(sql());
            echo "<br/><a href='javascript:window.history.go(-1)'>Go back &rarr;</a>";
    }
    else{
        // success
        echo "Changes saved.<br/>";
        echo "<script type='text/javascript'>window.opener.location.reload();</script>";
        echo "<a href='./?' onclick='window.close();return false;'>Continue &rarr;</a><br/>";
    }
}
else{

?>

<form action="?p=edit&post<?php if(isset($_GET['id'])) echo "&id={$_GET['id']}";?>" method="post">
    <table class='edit_form' border="1">
    
    <tr>
        <td>Hardware</td>
        <td><input name="hardware" value="<?php echo get_val($mode, 'Hardware');?>"/></td>
    </tr>
    
    <tr>
        <td>Type</td>
        <td>
            <select name="type">
                <option value="1" <?php echo get_selected($mode, 'Type', 1);?>>Consumer (Server)</option>
                <option value="2" <?php echo get_selected($mode, 'Type', 2);?>>Provider (UPS)</option>
                <option value="3" <?php echo get_selected($mode, 'Type', 3);?>>Other</option>
            </select>
        </td>
    </tr>

    <tr>
        <td>Group</td>
        <td>
            <select name="group">
                <?php
                    $q = sql_query("SELECT `ID`,`Name` FROM `groups` ORDER BY `ID` ASC");
                    while($r = mysqli_fetch_row($q)){
                        echo "<option value='{$r[0]}' ";
                        echo get_selected($mode, 'Group', $r[0]);
                        echo ">{$r[1]}</option>";
                    }
                    mysqli_free_result($q);
                ?>
            </select>
        </td>
    <tr>
        <td>Position</td>
        <td>
            <select name="rack">
                <?php
                    $q = sql_query("SELECT `RackId` FROM `racks` ORDER BY `RackId` DESC");
                    while($r = mysqli_fetch_row($q)){
                        echo "<option value='{$r[0]}' ";
                        echo get_selected($mode, 'Rack', $r[0]);
                        echo ">Rack {$r[0]}</option>";
                    }
                    mysqli_free_result($q);
                ?>
            </select>
            
            <select name="position">
                <?php
                    for($i = 42; $i >= 0; $i--){
                        echo "<option value='$i' ";
                        echo get_selected($mode, 'Position', $i);
                        echo ">Unit $i</option>";
                    }
                ?>
            </select>
        </tr>
    </tr>
    
    <tr>
        <td>Height</td>
        <td>
             <select name="height">
                <?php
                    for($i = 1; $i <= 20; $i++){
                        echo "<option value='$i' ";
                        echo get_selected($mode, 'Height', $i);
                        echo ">$i U</option>";
                    }
                ?>
            </select>
        </td>
        </tr>
    </tr>

    <tr>
        <td>Consumer Parameters</td>
        <td>
            <i>Ignore this section if configuring a provider (or other)</i>
            <table border="0" width="100%">
                <tr>
                    <td>Total Consumption</td>
                    <td><input name="load" value="<?php echo get_val($mode, 'TotalLoad');?>"/></td>
                </tr>
                <tr>
                    <td>Power Supply 1</td>
                    <td>
                        <select name="ref1">
                            <?php print_refs($mode,'Ref1'); ?>
                        </select>
                        <input type='checkbox' name="ref1_f" value="1" <?php echo get_flag_checked($mode, 1); ?>/>
                    </td>
                </tr>

                <tr>
                    <td>Power Supply 2</td>
                    <td>
                        <select name="ref2">
                            <?php print_refs($mode,'Ref2'); ?>
                        </select>
                        <input type='checkbox' name="ref2_f" value="1" <?php echo get_flag_checked($mode, 2); ?>/>
                    </td>
                </tr>

                <tr>
                    <td>Power Supply 3</td>
                    <td>
                        <select name="ref3">
                            <?php print_refs($mode,'Ref3'); ?>
                        </select>
                        <input type='checkbox' name="ref3_f" value="1" <?php echo get_flag_checked($mode, 4); ?>/>
                    </td>
                </tr>

                <tr>
                    <td>Power Supply 4</td>
                    <td>
                        <select name="ref4">
                            <?php print_refs($mode,'Ref4'); ?>
                        </select>
                        <input type='checkbox' name="ref4_f" value="1" <?php echo get_flag_checked($mode, 8); ?>/>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td>Provider Parameters</td>
        <td>
            <i>Ignore this section if configuring a consumer (or other)</i>
            <table border="0" width="100%">
                <tr>
                    <td>Capacity</td>
                    <td>
                         <input name="capacity" value="<?php echo get_val($mode, 'Capacity'); ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>Runtime =</td>
                    <td>
                        <input name="formulaa" value="<?php echo get_val($mode, 'FormulaA'); ?>"/> * e^(<input name="formulab" value="<?php echo get_val($mode, 'FormulaB'); ?>"/> * W)
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td>Comments</td>
        <td>
            <textarea name='comment' rows="5" cols="52"><?php echo get_val($mode, 'Comment'); ?></textarea>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
        <td>
            <input type='submit' value="Save" name='action'/>
            <?php if($mode) echo "<input type='submit' onclick=\"return confirm('Are you sure you want to delete this?')\" value='Delete' name='action'/>"; ?>
        </td>
    </tr>
    
    </table>
</form>

<?php

}

?>
