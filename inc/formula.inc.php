<?php

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

// gets the formula for ups with specified $id from the db, and calculates the runtime under specified $load
function apply_formula($id, $load){
    $q = sql_query("SELECT `FormulaA`,`FormulaB` FROM `entities` WHERE `ID`='$id'");
    $r = mysqli_fetch_row($q);
    return $r[0] * exp($r[1] * $load);
}
