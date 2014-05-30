<?php

function set_conf($i, $v){
	if(!isset($GLOBALS['_CONF_']))
		$GLOBALS['_CONF_'] = array();
	$GLOBALS['_CONF_'][$i] = $v;
}

function get_conf($i){
	if(!isset($GLOBALS['_CONF_']))
		$GLOBALS['_CONF_'] = array();
	return $GLOBALS['_CONF_'][$i];
}
