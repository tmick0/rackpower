<?php

// used to set the title of a page from within the content scripts
function set_title($t){
    $GLOBALS['_TITLE_'] = $t;
}

// returns the title if set
function get_title(){
    if(isset($GLOBALS['_TITLE_']))
        return $GLOBALS['_TITLE_'];
    else return "undefined";
}

// executes a requested content script, verifying that it is within the 'content' directory
function show_content($file){
    $content_path = realpath(dirname(__FILE__)."/../content/");
    if(realpath(dirname("$content_path/$file")) != $content_path)
        exit("security error: show_content called on invalid file path");
    else{
        require("$content_path/$file");
    }
}
