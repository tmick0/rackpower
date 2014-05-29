<?php

function set_conf($i, $v)
	$GLOBALS['_CONF_'][$i] = $v;
}

function get_conf($i){
	return $GLOBALS['_CONF_'][$i];
}
