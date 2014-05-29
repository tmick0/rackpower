<?php

function is_user_authed(){
    // TODO: make this better for the love of god
    if(!isset($_SESSION['authed']) || $_SESSION['authed'] != true)
        return false;
    else
        return true;
}

function auth_user(){
    $_SESSION['authed'] = true;
}

function deauth_user(){
    $_SESSION['authed'] = false;
}
