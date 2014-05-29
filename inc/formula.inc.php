<?php

function apply_formula($id, $load){
    $q = sql_query("SELECT `FormulaA`,`FormulaB` FROM `entities` WHERE `ID`='$id'");
    $r = mysqli_fetch_row($q);
    return $r[0] * exp($r[1] * $load);
}
