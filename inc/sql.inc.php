<?php

// global sql connection handle
$GLOBALS['_SQL_'] = null;

// returns sql connection handle, connecting if not already connected
function sql(){
    if($GLOBALS['_SQL_'] == null)
        $GLOBALS['_SQL_'] = mysqli_connect(get_conf('sql_host'), get_conf('sql_user'), get_conf('sql_pass'), get_conf('sql_db'));

    if($GLOBALS['_SQL_']->connect_errno) exit("sql connection failed: <br/> {$GLOBALS['_SQL_']->connect_error}");
    
    return $GLOBALS['_SQL_'];
}

// wrapper for mysqli_query function
function sql_query($q){
    return mysqli_query(sql(), $q);
}

// wrapper for mysqli_real_escape_string function
function sql_esc($s){
    return mysqli_real_escape_string(sql(), $s);
}
