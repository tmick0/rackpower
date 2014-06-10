<?php

// count bits set in an integer (used in RefFlags)
function bits_set($n){
    $s = 0;
    while($n > 0){
        $s += $n & 0x01;
        $n = $n >> 1;
    }
    return $s;
}
