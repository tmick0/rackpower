<?php

$GLOBALS['_SQL_'] = null;

function sql(){
    if($GLOBALS['_SQL_'] == null)
        $GLOBALS['_SQL_'] = mysqli_connect(get_conf('sql_host'), get_conf('sql_user'), get_conf('sql_pass'), get_conf('sql_db'));

    if($GLOBALS['_SQL_']->connect_errno) exit("sql connection failed: <br/> {$GLOBALS['_SQL_']->connect_error}");
    
    return $GLOBALS['_SQL_'];
}

function sql_query($q){
    return mysqli_query(sql(), $q);
}

function sql_esc($s){
    return mysqli_real_escape_string(sql(), $s);
}
