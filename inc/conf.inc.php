<?php

// store config pair in global hashmap
function set_conf($i, $v){
	if(!isset($GLOBALS['_CONF_']))
		$GLOBALS['_CONF_'] = array();
	$GLOBALS['_CONF_'][$i] = $v;
}

// get config value from key in global hashmap
function get_conf($i){
	if(!isset($GLOBALS['_CONF_']))
		$GLOBALS['_CONF_'] = array();
	return $GLOBALS['_CONF_'][$i];
}
