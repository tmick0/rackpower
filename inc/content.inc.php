<?php

function set_title($t){
    $GLOBALS['_TITLE_'] = $t;
}

function get_title(){
    if(isset($GLOBALS['_TITLE_']))
        return $GLOBALS['_TITLE_'];
    else return "undefined";
}

function show_content($file){
    $content_path = realpath(dirname(__FILE__)."/../content/");
    if(realpath(dirname("$content_path/$file")) != $content_path)
        exit("security error: show_content called on invalid file path");
    else
        include("$content_path/$file");
}
